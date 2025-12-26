<?php

namespace App\Livewire\Forms\Core\Person;

use App\Models\Person;
use App\Models\Role;
use App\Models\User;
use App\Traits\Core\Common\ModelFileTrait;
use App\Traits\Core\Common\ModelImageTrait;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Form;

class AddForm extends Form
{
    use ResponseTrait, ModelImageTrait, ModelFileTrait;

    public array $user = [
        'email' => null,
    ];

    public array $person = [
        "employee_number" => null,
        "social_number" => null,

        "last_name_ar" => null,
        "first_name_ar" => null,
        "last_name_fr" => null,
        "first_name_fr" => null,

        "card_number" => null,
        "birth_place_ar" => null,
        "birth_place_fr" => null,
        "birth_place_en" => null,
        "birth_date" => null,

        "address_fr" => null,
        "address_ar" => null,
        "address_en" => null,

        "phone" => null,
    ];

    public $image = null;

    private const DEFAULT_PASSWORD = "Vide=1342";
    private const IMAGE_MAX_SIZE   = 10000;
    private const MIN_YEAR        = '1920-01-01';
    private const MIN_AGE         = 18;

    // ------------------------------------------------------------
    // VALIDATION RULES
    // ------------------------------------------------------------
    public function rules(): array
    {
        $unique = fn ($table, $column) =>
            Rule::unique($table, $column)->whereNull('deleted_at');

        return [

            // USER (optional)
            'user.email' => [
                'nullable',
                'email',
                'max:255',
                $unique('users', 'email'),
            ],

            // PERSON
            'person.last_name_fr' => 'required|string|min:3|max:100',
            'person.first_name_fr' => 'required|string|min:3|max:100',
            'person.last_name_ar' => 'required|string|min:3|max:100',
            'person.first_name_ar' => 'required|string|min:3|max:100',

            'person.employee_number' => ['nullable', 'string', 'size:10', $unique('persons', 'employee_number')],
            'person.social_number'   => ['nullable', 'string', 'size:20', $unique('persons', 'social_number')],

            'person.card_number' => ['nullable', 'string', 'min:6', $unique('persons', 'card_number')],

            'person.birth_place_fr' => 'nullable|string|min:3|max:200',
            'person.birth_place_ar' => 'nullable|string|min:3|max:200',
            'person.birth_place_en' => 'nullable|string|min:3|max:200',

            'person.birth_date' => [
                'nullable', 'date', 'after:' . self::MIN_YEAR,
                'before:' . now()->subYears(self::MIN_AGE)->format('Y-m-d'),
            ],

            'person.address_fr' => 'nullable|string|min:3|max:400',
            'person.address_ar' => 'nullable|string|min:3|max:400',
            'person.address_en' => 'nullable|string|min:3|max:400',

            'person.phone' => [
                'nullable', 'regex:/^(05|06|07)\d{8}$/', $unique('persons', 'phone')
            ],

            // IMAGE
            'image' => 'nullable|file|mimes:jpeg,png,gif,ico,webp|max:' . self::IMAGE_MAX_SIZE,
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
    // SAVE LOGIC
    // ------------------------------------------------------------
    public function save(): array
    {
        try {
            $validated = $this->validate();

            return DB::transaction(function () use ($validated) {

                // CREATE PERSON
                $person = Person::create($validated['person']);

                // CREATE USER ONLY IF EMAIL EXISTS
                if (!empty($validated['user']['email']) || $this->image) {

                    $user = $this->createUser($person, $validated);

                    // Upload image
                    $this->handleImageUpload($user);

                    // Assign roles
                    $this->assignRoles($user);
                }

                return $this->response(true, __('forms.person.responses.add_success'));
            });

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->response(false, errors: $e->validator->errors()->all());
        } catch (\Exception $e) {
            Log::error('Person/User creation failed', ['message' => $e->getMessage()]);
            return $this->response(false, errors: __('forms.common.errors.default'));
        }
    }

    // ------------------------------------------------------------
    // CREATE USER
    // ------------------------------------------------------------
    protected function createUser(Person $person, array $validated): User
    {
        return User::create([
            'email'     => $validated['user']['email'],
            'password'  => Hash::make(self::DEFAULT_PASSWORD),
            'person_id' => $person->id,
            'name'   => "{$person->last_name_fr} {$person->first_name_fr}",
        ]);
    }

    // ------------------------------------------------------------
    // UPLOAD IMAGE
    // ------------------------------------------------------------
    protected function handleImageUpload(User $user): void
    {
        if ($this->image) {
            $this->uploadAndCreateImage(
                $this->image,
                $user->id,
                User::class,
                "avatar"
            );
        }
    }

    // ------------------------------------------------------------
    // ROLE ASSIGNMENT
    // ------------------------------------------------------------
    protected function assignRoles(User $user): void
    {
        $roles = $this->getRolesToAssign();

        if (!empty($roles)) {
            $user->roles()->attach($roles);
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

        // Add "service_admin" if the current user is establishment_admin
        if (auth()->check() &&
            auth()->user()->roles()->where('slug', 'establishment_admin')->exists()) {

            if ($serviceAdmin = Role::where('slug', 'service_admin')->first()) {
                $roles[] = $serviceAdmin->id;
            }
        }

        return $roles;
    }
}
