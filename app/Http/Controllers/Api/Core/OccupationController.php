<?php

namespace App\Http\Controllers\Api\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\Occupation\CreateRequest;
use App\Http\Requests\Core\Occupation\UpdateRequest;
use App\Http\Resources\Core\OccupationResource;
use App\Models\Occupation;
use App\Models\Person;
use App\Models\User;
use App\Traits\Core\Api\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class OccupationController extends Controller
{
    use ResponseTrait;

    /**
     * List all occupations for the specified user.
     */
    public function index(string $lang, Person $person): JsonResponse
    {
        try {
            $occupations = $person->occupations()->latest()->get();

            return $this->responseCollection(
                'occupation',
                OccupationResource::collection($occupations),
                ['message' => __('forms.occupation.responses.index_success', ['person' => $person->full_name])]
            );

        } catch (\Throwable $e) {
            Log::error("OccupationController@index error: {$e->getMessage()}", [
                'person_id' => $person->id,
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->responseError('occupation', __('forms.common.errors.default'), 'server_error', 500);
        }
    }

    /**
     * Create a new occupation for a user.
     */
    public function store(string $lang, CreateRequest $request, Person $person): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['person_id'] = $person->id;

            $occupation = $person->occupations()->create($data);

            return $this->responseSuccess('occupation', $occupation->id, [
                'message' => __('forms.occupation.responses.add_success', ['person' => $person->full_name]),
                'occupation' => $occupation,
            ]);

        } catch (\Throwable $e) {
            Log::error("OccupationController@store error: {$e->getMessage()}", [
                'target_person_id' => $person->id,
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->responseError('occupation', __('forms.common.errors.default'), 'server_error', 500);
        }
    }

    /**
     * Show a specific occupation for a user.
     */
    public function show(string $lang, Person $person, Occupation $occupation): JsonResponse
    {
        try {
            return $this->responseSuccess('occupation', $occupation->id, [
                'occupation' => new OccupationResource($occupation),
            ]);

        } catch (\Throwable $e) {
            Log::error("OccupationController@show error: {$e->getMessage()}", [
                'target_person_id' => $person->id,
                'occupation_id' => $occupation->id,
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->responseError('occupation', __('forms.common.errors.default'), 'server_error', 500);
        }
    }

    /**
     * Update a specific occupation for a user.
     */
    public function update(string $lang, UpdateRequest $request, Person $person, Occupation $occupation): JsonResponse
    {
        try {
            $validated = $request->validated();
            $occupation->update($validated);

            return $this->responseSuccess('occupation', $occupation->id, [
                'message' => __('forms.occupation.responses.update_success'),
                'occupation' => $occupation,
            ]);

        } catch (\Throwable $e) {
            Log::error("OccupationController@update error: {$e->getMessage()}", [
                'target_person_id' => $person->id,
                'occupation_id' => $occupation->id,
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->responseError('occupation', __('forms.common.errors.default'), 'server_error', 500);
        }
    }

    /**
     * Delete the specified occupation.
     */
    public function destroy(string $lang, Occupation $occupation): JsonResponse
    {
        try {
            $occupation->delete();

            return $this->responseSuccess('occupation', $occupation->id, [
                'message' => __('forms.occupation.responses.delete_success'),
            ]);

        } catch (\Throwable $e) {
            Log::error("OccupationController@destroy error: {$e->getMessage()}", [
                'occupation_id' => $occupation->id,
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->responseError('occupation', __('forms.common.errors.default'), 'server_error', 500);
        }
    }
}
