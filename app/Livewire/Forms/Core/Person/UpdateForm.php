<?php

namespace App\Livewire\Forms\Core\Person;

use App\Models\Person;
use App\Models\Role;
use App\Models\User;
use App\Traits\Core\Common\ModelFileTrait;
use App\Traits\Core\Common\ModelImageTrait;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Form;

class UpdateForm extends Form
{
    use ResponseTrait, ModelImageTrait, ModelFileTrait;

    public $image;

    public array $user = [
        'is_active' => null,
        'email'     => null,
    ];

    public array $person = [
        'id' => null,

        'last_name_fr' => null,
        'first_name_fr' => null,
        'last_name_ar' => null,
        'first_name_ar' => null,

        'card_number' => null,
        'birth_place_fr' => null,
        'birth_place_ar' => null,
        'birth_place_en' => null,
        'birth_date' => null,

        'address_fr' => null,
        'address_ar' => null,
        'address_en' => null,

        'phone' => null,
        'employee_number' => null,
        'social_number' => null,
        'is_paid' => null,
    ];

    // ------------------------------------------------------------
    // VALIDATION
    // ------------------------------------------------------------
    public function rules(): array
    {
        $personId = $this->person['id'];

        return [

            'user.is_active' => 'nullable|boolean',
            'user.email'     => 'nullable|email|max:255',

            // PERSON
            'person.id' => 'required|exists:persons,id',

            'person.last_name_fr'  => 'required|string|min:3|max:100',
            'person.first_name_fr' => 'required|string|min:3|max:100',
            'person.last_name_ar'  => 'required|string|min:3|max:100',
            'person.first_name_ar' => 'required|string|min:3|max:100',

            'person.employee_number' => [
                'nullable',
                'string',
                'size:10',
                Rule::unique('persons', 'employee_number')->ignore($personId)->whereNull('deleted_at'),
            ],

            'person.social_number' => [
                'nullable',
                'string',
                'size:20',
                Rule::unique('persons', 'social_number')->ignore($personId)->whereNull('deleted_at'),
            ],

            'person.card_number' => [
                'nullable',
                'string',
                'min:6',
                Rule::unique('persons', 'card_number')->ignore($personId)->whereNull('deleted_at'),
            ],

            'person.birth_place_fr' => 'nullable|string|min:3|max:200',
            'person.birth_place_ar' => 'nullable|string|min:3|max:200',
            'person.birth_place_en' => 'nullable|string|min:3|max:200',

            'person.birth_date' => [
                'nullable',
                'date',
                'date_format:Y-m-d',
                'after:1920-01-01',
                'before:' . Carbon::now()->subYears(18)->format('Y-m-d'),
            ],

            'person.address_fr' => 'nullable|string|min:3|max:400',
            'person.address_ar' => 'nullable|string|min:3|max:400',
            'person.address_en' => 'nullable|string|min:3|max:400',

            'person.phone' => [
                'nullable',
                'regex:/^(05|06|07)\d{8}$/',
                Rule::unique('persons', 'phone')->ignore($personId)->whereNull('deleted_at'),
            ],

            'person.is_paid' => 'nullable|boolean',

            'image' => 'nullable|file|mimes:jpeg,png,gif,ico,webp|max:10000',
        ];
    }

    public function messages(): array
    {
        return [
            'person.phone.regex' => __("forms.common.errors.not_match.phone"),
        ];
    }

            public function validationAttributes(): array
    {
        return $this->returnTranslatedResponseAttributes('person', [
            'image', 'name', 'password', 'email','last_name_fr','last_name_ar','first_name_fr','first_name_ar','employee_number','social_number','card_number','birth_place_fr','birth_place_ar','birth_place_en','birth_date','address_fr','address_ar','address_en','phone'
        ]);
    }
    // ------------------------------------------------------------
    // SAVE
    // ------------------------------------------------------------
    public function save(Person $person)
    {
        try {
            $validated = $this->validate();

            return DB::transaction(function () use ($validated, $person) {

                $user = $person->user;

                // -------------------------------
                // UPDATE PERSON
                // -------------------------------
                $person->update($validated['person']);

                // -------------------------------
                // USER EXISTS → UPDATE
                // -------------------------------
                if ($user) {

                    $user->update([
                        'is_active' => $validated['user']['is_active'] ?? $user->is_active,
                    ]);

                    // Update avatar (NO duplicate image creation)
                    if ($this->image) {
                        $this->uploadAndUpdateImage(
                            $this->image,
                            $user->id,
                            User::class,
                            'avatar'
                        );
                    }
                }
                // -------------------------------
                // USER DOES NOT EXIST → CREATE
                // -------------------------------
                else if (!empty($validated['user']['email']) || $this->image) {

                    $user = $this->createUser($person, $validated);

                    if ($this->image) {
                        $this->uploadAndCreateImage(
                            $this->image,
                            $user->id,
                            User::class,
                            "avatar"
                        );
                    }

                    // Assign default roles
                    $this->assignRoles($user);
                }

                return $this->response(true, __('forms.person.responses.update_success', ['name'=> $person->full_name]));
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Person update error: ' . $e->getMessage());
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }



    protected function createUser(Person $person, array $validated): User
    {
        return User::create([
            'email'     => $validated['user']['email'],
            'password'  => Hash::make('Vide=1342'),
            'person_id' => $person->id,
            'name'      => "{$person->last_name_fr} {$person->first_name_fr}",
        ]);
    }

    protected function assignRoles(User $user): void
    {
        $roles = $this->getRolesToAssign();

        if (!empty($roles)) {
            $user->roles()->syncWithoutDetaching($roles);
        }
    }

    protected function getRolesToAssign(): array
    {
        $roles = [];

        // Default role
        $defaultSlug = config('defaultRole.default_role_slug', 'user');
        if ($defaultRole = Role::where('slug', $defaultSlug)->first()) {
            $roles[] = $defaultRole->id;
        }

        // Add "service_admin" if current user is "establishment_admin"
        if (
            auth()->check() &&
            auth()->user()->roles()->where('slug', 'establishment_admin')->exists()
        ) {
            if ($serviceAdmin = Role::where('slug', 'service_admin')->first()) {
                $roles[] = $serviceAdmin->id;
            }
        }

        return $roles;
    }
}
