<?php

namespace App\Http\Controllers\Api\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\User\BulkInsertRequest;
use App\Http\Requests\Core\User\PatchRequest;
use App\Http\Requests\Core\User\StoreRequest;
use App\Http\Resources\Core\UserResource;
use App\Models\File;
use App\Models\Image;
use App\Models\Role;
use App\Models\User;
use App\Traits\Core\Api\ImportTrait;
use App\Traits\Core\Api\ResponseTrait;
use App\Traits\Core\Common\ModelFileTrait;
use App\Traits\Core\Common\ModelImageTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    use ResponseTrait, ModelImageTrait, ModelFileTrait, ImportTrait;

    private string $locale;
    private string $localeArAndFrOnly;

    public function __construct()
    {
        $this->localeArAndFrOnly = in_array(app()->getLocale(), ['fr', 'ar']) ? app()->getLocale() : 'fr';
        $this->locale =  app()->getLocale();
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $perPage        = (int) $request->input('perPage', 15);
            $fullName      = $request->input('fullName');
            $name       = $request->input('name');
            $email          = $request->input('email');
            $employeeNumber = $request->input('employeeNumber');

            /** Detect if text contains Arabic */
            $isArabic = $fullName && preg_match('/\p{Arabic}/u', $fullName);

            /** Choose the correct columns */
            $lastNameColumn  = $isArabic ? 'last_name_ar'  : "last_name_{$this->localeArAndFrOnly}";
            $firstNameColumn = $isArabic ? 'first_name_ar' : "first_name_{$this->localeArAndFrOnly}";

            /** CONCAT SQL expression */
            $fullNameConcat = DB::raw("CONCAT($lastNameColumn, ' ', $firstNameColumn)");

            /** Build Query */
            $query = User::query()
                ->with(['person'])

                /** Full name search using CONCAT */
                ->when($fullName, function ($q) use ($fullNameConcat, $fullName) {
                    $q->whereHas(
                        'person',
                        fn($p) =>
                        $p->where($fullNameConcat, 'LIKE', "%{$fullName}%")
                    );
                })
                ->when($name, function ($q) use ($name) {

                    $q->where("name", 'LIKE', "%{$name}%");
                })

                /** Email search */
                ->when(
                    $email,
                    fn($q) =>
                    $q->where('email', 'LIKE', "%{$email}%")
                )

                /** Employee number search */
                ->when(
                    $employeeNumber,
                    fn($q) =>
                    $q->whereHas(
                        'person',
                        fn($p) =>
                        $p->where('employee_number', 'LIKE', "%{$employeeNumber}%")
                    )
                )

                /** Sorting */
                ->orderBy(
                    $request->input('sortBy', 'id'),
                    $request->input('sortDirection', 'asc')
                )

                ->paginate($perPage)
                ->appends(
                    $request->only(['perPage', 'sortBy', 'sortDirection', 'fullName', 'email', 'employeeNumber'])
                );

            /** Format Response */
            $mapped = $query->getCollection()->map(function ($user) {

                $activeOccupation = $user->person?->occupations->firstWhere('is_active', true);

                return [
                    'id'              => $user->id,
                    'name'            => $user->name,
                    'email'           => $user->email,
                    'is_active'       => $user->is_active,

                    // person
                    'full_name_ar'    => $user->person?->full_name_ar,
                    'full_name_fr'    => $user->person?->full_name_fr,
                    'employee_number' => $user->person?->employee_number,
                    'social_number'   => $user->person?->social_number,
                    'card_number'     => $user->person?->card_number,
                    'birth_date'      => $user->person?->birth_date,
                    'birth_place_fr'  => $user->person?->birth_place_fr,
                    'birth_place_ar'  => $user->person?->birth_place_ar,
                    'birth_place_en'  => $user->person?->birth_place_en,

                    // personnel info
                    'phone'           => $user->person?->phone,
                    // occupation
                    'specialty'       => $activeOccupation?->specialty?->getLocalizedDesignationAttribute(),
                    'grade'           => $activeOccupation?->grade?->getLocalizedDesignationAttribute(),
                ];
            });

            $query->setCollection($mapped);

            return $this->responseCollection('user', $query);
        } catch (\Throwable $e) {
            Log::error('UserController@index error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->responseError(__('forms.common.errors.default'), 500);
        }
    }


    public function show(string $lang, User $user): JsonResponse
    {
        try {
            $user->loadMissing(['person']);
            return $this->responseSuccess('users', $user->id, ['user' => new UserResource($user)]);
        } catch (\Throwable $e) {
            Log::error('UserController@show error', ['user_id' => $user->id ?? null, 'error' => $e->getMessage()]);
            return $this->responseError('users', __('forms.common.errors.default'), 'show_failed', 500);
        }
    }

    public function store(string $lang, StoreRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            return DB::transaction(function () use ($data, $request) {

                $data["password"] = Hash::make($data['password']);
                $user = User::create($data);
                if ($request->hasFile('avatar')) {
                    $this->uploadAndCreateImage($request->file('avatar'), $user->id, User::class, 'avatar');
                }
                $user->update($data);

                $defaultRoleSlugs = [config('defaultRole.default_role_slug', 'user')];
                $user->roles()->attach(Role::whereIn('slug', $defaultRoleSlugs)->get());
                return $this->responseSuccess('users', $user->id, ['user' => new UserResource($user)]);
            });
        } catch (\Throwable $e) {
            Log::error('UserController@store error', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            return $this->responseError(__('forms.common.errors.default'), 500);
        }
    }
    public function update(string $lang, PatchRequest $request, User $user): JsonResponse
    {
        try {
            $data = $request->validated();
            return DB::transaction(function () use ($data, $user, $request) {

                if ($request->hasFile('avatar')) {
                    $this->uploadAndUpdateImage($request->file('avatar'), $user->id, User::class, 'avatar');
                }
                $user->update($data);

                return $this->responseSuccess('users', $user->id, ['user' => new UserResource($user)]);
            });
        } catch (\Throwable $e) {
            Log::error('UserController@update error', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            return $this->responseError(__('forms.common.errors.default'), 500);
        }
    }

    public function destroy(string $lang, User $user): JsonResponse
    {
        try {
            $currentUser = Auth::user();
            if ($user->id !== $currentUser->id) {
                return $this->responseError('users', __('api.users.destroy.no-access'), 'no_access', 429);
            }

            $this->deleteImages(Image::where('imageable_id', $user->id)->where('imageable_type', User::class)->get());
            $this->deleteFiles(File::where('fileable_id', $user->id)->where('fileable_type', User::class)->get());

            $user->delete();

            return $this->responseSuccess('users', null, ['message' => __('api.users.responses.destroy')]);
        } catch (\Throwable $e) {
            Log::error('UserController@destroy error', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            return $this->responseError('users', __('forms.common.errors.default'), 'destroy_failed', 500);
        }
    }

    public function bulkAddUsers(BulkInsertRequest $request): JsonResponse
    {
        try {
            $this->handleExcelImport($request->file('file'), 'UsersImport');
            return $this->responseSuccess('users', null, ['message' => __('api.users.responses.bulk_insert_success')]);
        } catch (\Throwable $e) {
            return $this->responseError('users', $e->getMessage(), 'bulk_insert_validation', 422, true);
        }
    }
}
