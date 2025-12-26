<?php

namespace App\Http\Controllers\Api\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\BankingInformation\CreateRequest;
use App\Http\Requests\Core\BankingInformation\UpdateRequest;
use App\Http\Resources\Core\BankingInformationResource;
use App\Models\BankingInformation;
use App\Models\Person;
use App\Traits\Core\Api\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BankingInformationController extends Controller
{
    use ResponseTrait;

    protected array $morphMap = [];

    public function __construct()
    {
        $this->morphMap = [
            'person' => Person::class,
        ];
    }

    /**
     * Resolve polymorphic model from type and ID.
     */
    private function resolveBankable(string $typeKey, int $id): array
    {
        $type = $this->morphMap[$typeKey] ?? null;

        if (!$type || !class_exists($type)) {
            return [null, null];
        }

        $model = $type::find($id);

        return [$type, $model];
    }

    /**
     * List banking information for a bankable entity.
     */
    public function index(string $lang, Request $request): JsonResponse
    {
        try {
            [$type, $model] = $this->resolveBankable(
                $request->input('bankable_type'),
                $request->input('bankable_id')
            );

            if (!$model) {
                return $this->responseError(
                    'bankingInformation',
                    __('forms.banking_information.errors.not_found'),
                    'not_found',
                    404
                );
            }

            $infos = $model->bankingInformation()->latest()->get();

            return $this->responseSuccess('bankingInformation', null, [
                'banking_information' => BankingInformationResource::collection($infos),
            ]);

        } catch (\Throwable $e) {
            Log::error("BankingInformationController@index error: {$e->getMessage()}", [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->responseError('bankingInformation', __('forms.common.errors.default'), 'server_error', 500);
        }
    }

    /**
     * Store a new banking information record.
     */
    public function store(string $lang, CreateRequest $request): JsonResponse
    {
        try {
            $info = BankingInformation::create($request->validated());

            return $this->responseSuccess('bankingInformation', $info->id, [
                'message' => __('forms.banking_information.responses.add_success'),
                'banking_information' => new BankingInformationResource($info),
            ]);

        } catch (\Throwable $e) {
            Log::error("BankingInformationController@store error: {$e->getMessage()}", [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->responseError('bankingInformation', __('forms.common.errors.default'), 'server_error', 500);
        }
    }

    /**
     * Show a specific banking information record.
     */
    public function show(string $lang, BankingInformation $bankingInformation): JsonResponse
    {
        try {
            return $this->responseSuccess('bankingInformation', $bankingInformation->id, [
                'banking_information' => new BankingInformationResource($bankingInformation),
            ]);

        } catch (\Throwable $e) {
            Log::error("BankingInformationController@show error: {$e->getMessage()}", [
                'banking_information_id' => $bankingInformation->id,
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->responseError('bankingInformation', __('forms.common.errors.default'), 'server_error', 500);
        }
    }

    /**
     * Update a specific banking information record.
     */
    public function update(string $lang, UpdateRequest $request, BankingInformation $bankingInformation): JsonResponse
    {
        try {
            $bankingInformation->update($request->validated());

            return $this->responseSuccess('bankingInformation', $bankingInformation->id, [
                'message' => __('forms.banking_information.responses.update_success'),
                'banking_information' => new BankingInformationResource($bankingInformation),
            ]);

        } catch (\Throwable $e) {
            Log::error("BankingInformationController@update error: {$e->getMessage()}", [
                'banking_information_id' => $bankingInformation->id,
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->responseError('bankingInformation', __('forms.common.errors.default'), 'server_error', 500);
        }
    }

    /**
     * Delete a banking information record.
     */
    public function destroy(string $lang, BankingInformation $bankingInformation): JsonResponse
    {
        try {
            $bankingInformation->delete();

            return $this->responseSuccess('bankingInformation', $bankingInformation->id, [
                'message' => __('forms.banking_information.responses.delete_success'),
            ]);

        } catch (\Throwable $e) {
            Log::error("BankingInformationController@destroy error: {$e->getMessage()}", [
                'banking_information_id' => $bankingInformation->id,
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->responseError('bankingInformation', __('forms.common.errors.default'), 'server_error', 500);
        }
    }

    /**
     * Ensure the banking information belongs to the given bankable input.
     */
    private function validateBankingOwnership(BankingInformation $bankingInformation, Request $request): bool
    {
        [$type] = $this->resolveBankable(
            $request->input('bankable_type'),
            $request->input('bankable_id')
        );

        return $bankingInformation->bankable_id == $request->input('bankable_id') &&
               $bankingInformation->bankable_type == $type;
    }
}
