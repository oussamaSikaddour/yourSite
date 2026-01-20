<?php

namespace App\Http\Controllers\Api\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\Auth\ManageRolesRequest;
use App\Http\Resources\Core\RoleResource;
use App\Models\Role;
use App\Models\User;
use App\Traits\Core\Api\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    use ResponseTrait;

    /**
     * List roles with optional filters and eager loading.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $userId = $request->input('userId');
            $withUsers = filter_var($request->input('withUsers'), FILTER_VALIDATE_BOOLEAN);
            $slug = $request->input('slug');

            $query = Role::query();

            if ($withUsers) {
                $query->with('users');
            }

            if ($userId) {
                $query->whereHas('users', fn($q) => $q->where('users.id', $userId));
            }

            if ($slug) {
                $query->where('slug', 'LIKE', "%{$slug}%");
            }

            $roles = $query->get();

            return $this->responseCollection('role', RoleResource::collection($roles));

        } catch (\Throwable $e) {
            Log::error('RoleController@index error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->responseError('role', __('forms.common.errors.default'), 'Server Error', 500);
        }
    }

    /**
     * Assign roles to a user (super-admin only).
     */
public function manageRoles(ManageRolesRequest $request): JsonResponse
{
    try {
        $data = $request->validated();
        $currentUser = Auth::user();
        $user = User::findOrFail($data['user_id']);

        $data['roles'] = array_map('intval', $data['roles'] ?? []);

        return DB::transaction(function () use ($data, $currentUser, $user) {

            $superAdminRoleId = (int) Role::where('slug', 'super_admin')->value('id');

            if ($superAdminRoleId) {
                $userIsSuperAdmin = $user->roles()
                    ->where('roles.id', $superAdminRoleId)
                    ->exists();

                $removingSuperAdmin = $userIsSuperAdmin
                    && ! in_array($superAdminRoleId, $data['roles'], true);

                if ($removingSuperAdmin) {
                    $otherSuperAdminsCount = User::whereKeyNot($user->id)
                        ->whereHas('roles', function ($q) use ($superAdminRoleId) {
                            $q->where('roles.id', $superAdminRoleId);
                        })
                        ->lockForUpdate() // optional extra safety
                        ->count();

                    if ($otherSuperAdminsCount === 0) {
                        return $this->responseError('role', __('forms.role.errors.unique_super_admin'), 422);
                    }
                }
            }

            $user->roles()->sync($data['roles']);

            $message = __('forms.role.responses.success');

            if ($user->id === $currentUser->id) {
                $currentUser->currentAccessToken()?->delete();
                $message = __('forms.role.responses.own_success');
            }

            $user->load('roles');

            return $this->responseSuccess('manageRoles', $user->id, [
                'message' => $message,
                'user' => $user->only('id', 'name', 'email'),
                'roles' => $user->roles->pluck('id', 'slug'),
            ]);
        });

    } catch (\Throwable $e) {
        Log::error('RoleController@manageRoles error', [
            'request_data' => $request->all(),
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return $this->responseError(__('forms.common.errors.default'), 500);
    }
}


}
