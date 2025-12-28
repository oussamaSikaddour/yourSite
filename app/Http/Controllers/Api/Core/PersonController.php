<?php

namespace App\Http\Controllers\Api\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\Person\BulkInsertRequest;
use App\Http\Requests\Core\Person\PatchRequest;
use App\Http\Requests\Core\Person\StoreRequest;
use App\Http\Resources\Core\PersonResource;
use App\Models\File;
use App\Models\Image;
use App\Models\Person;
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
use Illuminate\Support\Facades\DB as FacadesDB;

class PersonController extends Controller
{
    use ResponseTrait, ModelImageTrait, ModelFileTrait, ImportTrait;

    private string $locale;
    private string $localeArAndFrOnly;

    public function __construct()
    {
        $this->localeArAndFrOnly = in_array(app()->getLocale(), ['fr', 'ar'])
            ? app()->getLocale()
            : 'fr';

        $this->locale = app()->getLocale();
    }

    /* ===============================================================
     | INDEX
     ================================================================ */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage        = $request->integer('perPage', 15);
            $fullName       = $request->input('fullName');
            $name           = $request->input('name');
            $email          = $request->input('email');
            $employeeNumber = $request->input('employeeNumber');

            /** Detect Arabic for dynamic CONCAT */
            $isArabic = $fullName && preg_match('/\p{Arabic}/u', $fullName);

            $lastNameCol  = $isArabic ? 'persons.last_name_ar'  : "persons.last_name_{$this->localeArAndFrOnly}";
            $firstNameCol = $isArabic ? 'persons.first_name_ar' : "persons.first_name_{$this->localeArAndFrOnly}";

            /** SQL Safe CONCAT */
            $fullNameConcat = FacadesDB::raw("CONCAT($lastNameCol, ' ', $firstNameCol)");

            $query = Person::query()
                ->with(['user', 'occupations.specialty', 'occupations.grade'])

                /** Search user.name */
                ->when($name, fn($q) =>
                    $q->whereHas('user', fn($u) =>
                        $u->where('name', 'LIKE', "%{$name}%")
                    )
                )

                /** Search full name (Person CONCAT fields) */
                ->when($fullName, fn($q) =>
                    $q->where($fullNameConcat, 'LIKE', "%{$fullName}%")
                )

                /** Search email inside user */
                ->when($email, fn($q) =>
                    $q->whereHas('user', fn($u) =>
                        $u->where('email', 'LIKE', "%{$email}%")
                    )
                )

                /** Search employee number */
                ->when($employeeNumber, fn($q) =>
                    $q->where('employee_number', 'LIKE', "%{$employeeNumber}%")
                )

                /** Sorting */
                ->orderBy(
                    $request->input('sortBy', 'persons.id'),
                    $request->input('sortDirection', 'asc')
                )

                ->paginate($perPage)
                ->appends($request->only([
                    'perPage', 'sortBy', 'sortDirection',
                    'fullName', 'email', 'employeeNumber'
                ]));

            /** map results */
            $query->setCollection(
                $query->getCollection()->map(function ($person) {

                    $activeOccupation = $person->occupations
                        ->firstWhere('is_active', true);

                    return [
                        'id'              => $person->id,

                        // user
                        'name'            => $person->user?->name,
                        'email'           => $person->user?->email,
                        'is_active'       => $person->user?->is_active,

                        // person
                        'full_name_ar'    => $person->full_name_ar,
                        'full_name_fr'    => $person->full_name_fr,
                        'employee_number' => $person->employee_number,
                        'social_number'   => $person->social_number,
                        'card_number'     => $person->card_number,
                        'birth_date'      => $person->birth_date,
                        'birth_place_fr'  => $person->birth_place_fr,
                        'birth_place_ar'  => $person->birth_place_ar,
                        'birth_place_en'  => $person->birth_place_en,

                        // contact
                        'phone'           => $person->phone,

                        // occupation
                        'specialty'       => $activeOccupation?->specialty?->getLocalizedDesignationAttribute(),
                        'grade'           => $activeOccupation?->grade?->getLocalizedDesignationAttribute(),
                    ];
                })
            );

            return $this->responseCollection('person', $query);

        } catch (\Throwable $e) {

            Log::error('PersonController@index error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->responseError(__('forms.common.errors.default'), 500);
        }
    }

    /* ===============================================================
     | SHOW
     ================================================================ */
    public function show(string $lang, Person $person): JsonResponse
    {
        try {
            $person->loadMissing([
                'user',
                'occupations',
                'bankingInformation',
                'images'
            ]);

            return $this->responseSuccess('persons', $person->id, [
                'person' => new PersonResource($person)
            ]);

        } catch (\Throwable $e) {

            Log::error('PersonController@show error', [
                'person_id' => $person->id,
                'error'     => $e->getMessage()
            ]);

            return $this->responseError(__('forms.common.errors.default'), 500);
        }
    }

    /* ===============================================================
     | STORE
     ================================================================ */
    public function store(string $lang, StoreRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            return DB::transaction(function () use ($data, $request) {

                $person = Person::create($data['person']);

                $user = null;

                /** create user only when email exists */
                if (!empty($data['user']['email'])) {

                    $data['user']['password']  = Hash::make('Vide=1342');
                    $data['user']['person_id'] = $person->id;

                    $user = User::create($data['user']);

                    /** Image upload */
                    if ($request->hasFile('image')) {
                        $this->uploadAndCreateImage(
                            $request->file('image'),
                            $user->id,
                            User::class,
                            'avatar'
                        );
                    }

                    /** update derived name */
                    $user->update([
                        'name' => $person->last_name_fr . ' ' . $person->first_name_fr,
                    ]);
                }

                return $this->responseSuccess('persons', $person->id, [
                    'person' => new PersonResource($person),
                    'user'   => $user?->id,
                ]);
            });

        } catch (\Throwable $e) {

            Log::error('PersonController@store error', [
                'error' => $e->getMessage(),
            ]);

            return $this->responseError(__('forms.common.errors.default'), 500);
        }
    }

    /* ===============================================================
     | UPDATE
     ================================================================ */
    public function update(string $lang, PatchRequest $request, Person $person): JsonResponse
    {
        try {
            $data = $request->validated();

            return DB::transaction(function () use ($data, $person, $request) {

                /** 1. Update Person */
                if (!empty($data['person'])) {
                    $person->update($data['person']);
                }

                $user = $person->user;
                $inputUser = $data['user'] ?? [];

                /** A) update existing user */
                if ($user) {

                    if (!empty($inputUser)) {
                        $user->update($inputUser);
                    }

                    if ($request->hasFile('image')) {
                        $this->uploadAndUpdateImage(
                            $request->file('image'),
                            $user->id,
                            User::class,
                            'avatar'
                        );
                    }

                }
                /** B) create user if missing and email present */
                elseif (!empty($inputUser['email'])) {

                    $inputUser['password']  = Hash::make('Vide=1342');
                    $inputUser['person_id'] = $person->id;

                    $user = User::create($inputUser);

                    if ($request->hasFile('image')) {
                        $this->uploadAndCreateImage(
                            $request->file('image'),
                            $user->id,
                            User::class,
                            'avatar'
                        );
                    }
                }

                /** update derived name only if user exists */
                if ($user) {
                    $user->update([
                        'name' => $person->last_name_fr . ' ' . $person->first_name_fr
                    ]);
                }

                return $this->responseSuccess('persons', $person->id, [
                    'person' => new PersonResource($person)
                ]);
            });

        } catch (\Throwable $e) {

            Log::error('PersonController@update error', [
                'person_id' => $person->id,
                'error'     => $e->getMessage(),
            ]);

            return $this->responseError(__('forms.common.errors.default'), 500);
        }
    }

    /* ===============================================================
     | DESTROY
     ================================================================ */
    public function destroy(string $lang, Person $person): JsonResponse
    {
        try {
            $currentUser = Auth::user();

            if ($person->user?->id !== $currentUser->id) {
                return $this->responseError('persons', __('api.users.destroy.no-access'), 'no_access', 429);
            }

            $this->deleteImages(
                Image::where('imageable_id', $person->id)
                    ->where('imageable_type', Person::class)
                    ->get()
            );

            $this->deleteFiles(
                File::where('fileable_id', $person->id)
                    ->where('fileable_type', Person::class)
                    ->get()
            );

            $person->delete();

            return $this->responseSuccess('persons', null, [
                'message' => __('api.persons.responses.destroy')
            ]);

        } catch (\Throwable $e) {

            Log::error('PersonController@destroy error', [
                'person_id' => $person->id,
                'error'     => $e->getMessage()
            ]);

            return $this->responseError(__('forms.common.errors.default'), 500);
        }
    }

    /* ===============================================================
     | BULK INSERT
     ================================================================ */
    public function bulkAddUsers(BulkInsertRequest $request): JsonResponse
    {
        try {
            $this->handleExcelImport($request->file('file'), 'PersonsImport');

            return $this->responseSuccess('persons', null, [
                'message' => __('api.users.responses.bulk_insert_success')
            ]);

        } catch (\Throwable $e) {

            return $this->responseError(
                'persons',
                $e->getMessage(),
                'bulk_insert_validation',
                422,
                true
            );
        }
    }
}
