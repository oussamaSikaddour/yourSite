<?php

namespace App\Imports\Core;

use App\Models\Bank;
use App\Models\BankingInformation;
use App\Models\Person;
use App\Models\Role;
use App\Models\User;
use App\Rules\Core\ValidAccountNumber;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PersonsImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    protected array $errors = [];
    protected int $overallLineNumber = 1;
    protected array $userInfos = [];
    protected array $bankingInfos = [];
    protected array $existingAccountNumbers = [];
    protected array $banksCache = [];
    protected ?int $defaultRoleId = null;

    public function __construct(protected ?int $serviceId = null)
    {
        $this->existingAccountNumbers = BankingInformation::pluck('account_number')->toArray();
        $this->banksCache = Bank::pluck('id', 'acronym')->toArray();
        $this->defaultRoleId = Role::where('slug', config('defaultRole.default_role_slug', 'user'))->value('id');
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $lineNumber = $this->overallLineNumber + $index + 1;

            $cleanRow = $this->trimRowValues($row->toArray());
            $this->processRow($cleanRow, $lineNumber);
        }

        $this->overallLineNumber += $rows->count();
        $this->finalizeBulkInsert();

        if ($this->hasErrors()) {
            throw new \Exception($this->getFormattedErrors());
        }
    }

    protected function trimRowValues(array $row): array
    {
        return array_map(fn($value) => is_string($value) ? trim($value) : $value, $row);
    }

    protected function processRow(array $row, int $lineNumber): void
    {
        $validator = Validator::make($row, [
            'nom_francais'    => 'required|string|min:3|max:100',
            'prenom_francais' => 'required|string|min:3|max:100',
            'nom_arabe'       => 'nullable|string|min:3|max:100',
            'prenom_arabe'    => 'nullable|string|min:3|max:100',
            'banque'          => 'required|exists:banks,acronym',

            // Email is optional, and we allow it to be empty = do not create a user
            'e_mail' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->whereNull('deleted_at')
            ],

            'compte_bancaire' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (in_array($value, $this->existingAccountNumbers, true)) {
                        $fail(__('imports.banking_information.exist'));
                    }
                },
                new ValidAccountNumber(),
            ],
        ], [], [
            'nom_francais'    => __('imports.persons.nom_francais'),
            'prenom_francais' => __('imports.persons.prenom_francais'),
            'nom_arabe'       => __('imports.persons.nom_arabe'),
            'prenom_arabe'    => __('imports.persons.prenom_arabe'),
            'banque'          => __('imports.persons.banque'),
            'e_mail'          => __('imports.persons.e_mail'),
            'compte_bancaire' => __('imports.persons.compte_bancaire'),
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->errors[] = __("imports.line_number_error", ['number' => $lineNumber]) . " : " . $error;
            }
            return;
        }

        $this->prepareDataForBulkInsert($row);
    }

    protected function prepareDataForBulkInsert(array $row): void
    {
        $now = now();

        // Create Person
        $personId = Person::insertGetId([
            'last_name_fr'  => $row['nom_francais'],
            'first_name_fr' => $row['prenom_francais'],
            'last_name_ar'  => $row['nom_arabe'] ?? null,
            'first_name_ar' => $row['prenom_arabe'] ?? null,
            'is_paid'       => true,
            'created_at'    => $now,
            'updated_at'    => $now,
        ]);

        // Create banking info
        $this->bankingInfos[] = [
            'bankable_id'   => $personId,
            'bankable_type' => Person::class,
            'is_active'     => true,
            'bank_id'       => $this->banksCache[$row['banque']] ?? null,
            'account_number'=> $row['compte_bancaire'],
            'created_at'    => $now,
            'updated_at'    => $now,
        ];

        // Prevent duplicate within same file
        $this->existingAccountNumbers[] = $row['compte_bancaire'];

        // ❗ IMPORTANT: If email is not set → DO NOT CREATE USER
        if (empty($row['e_mail'])) {
            return;
        }

        // Prepare User insert
        $this->userInfos[] = [
            'person_id' => $personId,
            'name'      => "{$row['nom_francais']} {$row['prenom_francais']}",
            'email'     => $row['e_mail'],
            'password'  => Hash::make("Vide=1342"),
            'created_at'=> $now,
            'updated_at'=> $now,
        ];
    }

    protected function finalizeBulkInsert(): void
    {
        $now = now();

        // INSERT USERS (ONLY rows that had an email)
        foreach ($this->userInfos as $userInfo) {
            $userId = User::insertGetId($userInfo);

            // Assign default role if not exists
            if ($this->defaultRoleId &&
                !DB::table('role_user')->where([
                    'user_id' => $userId,
                    'role_id' => $this->defaultRoleId,
                ])->exists()
            ) {
                DB::table('role_user')->insert([
                    'user_id'    => $userId,
                    'role_id'    => $this->defaultRoleId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $this->userInfos = [];

        // Insert banking info
        if (!empty($this->bankingInfos)) {
            BankingInformation::insert($this->bankingInfos);
            $this->bankingInfos = [];
        }
    }

    protected function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    protected function getFormattedErrors(): string
    {
        return implode("\n", $this->errors);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
