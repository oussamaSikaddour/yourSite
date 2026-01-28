<?php

namespace App\Livewire\Core;

use App\Models\Bank;
use App\Models\Image;
use App\Models\Person;
use App\Traits\Core\Common\GeneralTrait;
use App\Traits\Core\Common\TableTrait;
use App\Traits\Core\Common\TextAndPdfTrait;
use App\Traits\Core\Web\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class PersonsTable extends Component
{
    use WithPagination, TableTrait, WithFileUploads, TextAndPdfTrait, ResponseTrait, GeneralTrait;

    #[Url]
    public string $fullName = '';

    #[Url]
    public ?string $email = null;

    #[Url]
    public ?string $employeeNumber = null;

    // ✅ NEW: bank filters
    #[Url]
    public ?string $bankAcronym = null;

    #[Url]
    public ?string $bankAccount = null;

    public ?string $isPaid = null;

    public string $local = 'fr';

    public $banksOptions = [];

    protected array $filterable = ['fullName', 'email', 'employeeNumber', 'bankAcronym', 'bankAccount'];

    protected array $validationRules = [
        'fullName' => ['nullable', 'string', 'max:255'],
        'email' => ['nullable', 'string', 'email', 'max:255'],
        'employeeNumber' => ['nullable', 'string', 'max:255'],
        'bankAcronym' => ['nullable', 'string', 'max:50'],
        'bankAccount' => ['nullable', 'string', 'max:100'],
    ];

    #[Computed]
    public function banks()
    {
        return Bank::get(['id', 'acronym']);
    }

    public function mount(): void
    {
        $this->banksOptions = $this->populateSelectorOption(
            $this->banks(),
            'acronym',
            'acronym',
            __('selectors.default.banks')
        );

        $this->local = app()->getLocale();
    }

    public function resetFilters(): void
    {
        $this->reset(['fullName', 'email', 'employeeNumber', 'bankAcronym', 'bankAccount']);
        $this->resetPage();
    }

    /**
     * Sorting that supports:
     * - virtual/accessor full_name_fr/full_name_ar/full_name
     * - joined columns user_email, bank_acronym, bank_account
     */
    private function applyPersonsSorting($query): void
    {
        $sortBy = $this->sortBy ?: 'created_at';
        $dir = strtoupper($this->sortDirection ?: 'DESC');
        $dir = $dir === 'ASC' ? 'ASC' : 'DESC';

        if ($sortBy === 'full_name_fr') {
            $query->orderBy(DB::raw("CONCAT(persons.last_name_fr, ' ', persons.first_name_fr)"), $dir);
            return;
        }

        if ($sortBy === 'full_name_ar') {
            $query->orderBy(DB::raw("CONCAT(persons.last_name_ar, ' ', persons.first_name_ar)"), $dir);
            return;
        }

        if ($sortBy === 'full_name') {
            $useArabic = app()->getLocale() === 'ar';
            $last  = $useArabic ? 'persons.last_name_ar'  : 'persons.last_name_fr';
            $first = $useArabic ? 'persons.first_name_ar' : 'persons.first_name_fr';
            $query->orderBy(DB::raw("CONCAT($last, ' ', $first)"), $dir);
            return;
        }

        if ($sortBy === 'user_email' || $sortBy === 'email') {
            $query->orderBy('users.email', $dir);
            return;
        }

        // ✅ bank sorts
        if ($sortBy === 'bank_acronym') {
            $query->orderBy('banks.acronym', $dir);
            return;
        }

        if ($sortBy === 'bank_account') {
            $query->orderBy('bi.account_number', $dir);
            return;
        }

        if (!str_contains($sortBy, '.')) {
            $sortBy = "persons.$sortBy";
        }

        $query->orderBy($sortBy, $dir);
    }

    #[Computed]
    public function persons()
    {
        $local = in_array($this->local, ['fr', 'en'], true) ? $this->local : 'fr';
        $isArabic = $this->fullName && preg_match('/\p{Arabic}/u', $this->fullName);

        $lastNameColumn  = $isArabic ? 'persons.last_name_ar'  : "persons.last_name_{$local}";
        $firstNameColumn = $isArabic ? 'persons.first_name_ar' : "persons.first_name_{$local}";
        $fullNameConcat  = DB::raw("CONCAT($lastNameColumn, ' ', $firstNameColumn)");

        /**
         * ✅ Subquery: active banking info for Person (no duplicates, no groupBy)
         * If your DB guarantees one active row per person, this is enough.
         */
        $activeBankingSub = DB::table('banking_information')
            ->select([
                'banking_information.bankable_id',
                'banking_information.bank_id',
                'banking_information.account_number',
            ])
            ->whereNull('banking_information.deleted_at')
            ->where('banking_information.bankable_type', Person::class)
            ->where('banking_information.is_active', 1);

        $query = Person::query()
            ->with('user')
            ->leftJoin('users', 'persons.id', '=', 'users.person_id')

            ->leftJoinSub($activeBankingSub, 'bi', function ($join) {
                $join->on('bi.bankable_id', '=', 'persons.id');
            })

            ->leftJoin('banks', function ($join) {
                $join->on('banks.id', '=', 'bi.bank_id')
                    ->whereNull('banks.deleted_at');
            })

            // ✅ filters
            ->when(!empty($this->fullName), function ($q) use ($fullNameConcat) {
                $q->where($fullNameConcat, 'like', "%{$this->fullName}%");
            })
            ->when(!empty($this->email), function ($q) {
                $q->where('users.email', 'like', "%{$this->email}%");
            })
            ->when(!empty($this->employeeNumber), function ($q) {
                $q->where('persons.employee_number', 'like', "%{$this->employeeNumber}%");
            })
            ->when(!empty($this->bankAcronym), function ($q) {
                $q->where('banks.acronym', 'like', "%{$this->bankAcronym}%");
            })
            ->when(!empty($this->bankAccount), function ($q) {
                $q->where('bi.account_number', 'like', "%{$this->bankAccount}%");
            })

            ->select([
                'persons.*',
                'users.email as user_email',
                'banks.acronym as bank_acronym',
                'bi.account_number as bank_account',
            ]);

        // ✅ apply safe sorting (supports accessor columns)
        $this->applyPersonsSorting($query);

        return $query->paginate($this->perPage ?? 20);
    }

    public function updated(string $property): void
    {
        if (in_array($property, $this->filterable, true) || $property === 'perPage') {
            $this->resetPage();
        }

        if (array_key_exists($property, $this->validationRules)) {
            try {
                $this->validateOnly($property, $this->validationRules);
            } catch (ValidationException $e) {
                $this->dispatch('open-errors', $e->validator->errors()->all());
            }
        }

        if ($property === 'excelFile') {
            $errorsFileData = $this->whenExcelFileUploaded(
                "Core\PersonsImport",
                __('tables.persons.excel.upload.success')
            );

            if (is_array($errorsFileData)) {
                $this->dispatch('errors-file-data', errorsFileData: $errorsFileData);
            }
        }
    }

    public function openDeletePersonDialog($person): void
    {
        $data = [
            "question" => "dialogs.title.person",
            "details" => ["person", $person['full_name']],
            "actionEvent" => [
                "event" => "delete-person",
                "parameters" => $person
            ],
        ];

        $this->dispatch("open-dialog", $data);
    }

    #[On("delete-person")]
    public function deletePerson(Person $person): void
    {
        try {
            $images = Image::where([
                ['imageable_id', $person->id],
                ['imageable_type', Person::class],
            ])->get();

            if ($images->isNotEmpty()) {
                $this->deleteImages($images);
            }

            $person->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting person: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    #[On('errors-file-data')]
    public function downloadUsersErrorsTextFile($errorsFileData)
    {
        return $this->streamFileDownload($errorsFileData['filePath'], $errorsFileData['fileName']);
    }

    public function generateEmptyPersonsExcel()
    {
        return $this->generateEmptyExcelWithHeaders("personsVide", [
            'Nom (français)',
            'Prénom (français)',
            'Nom (Arabic)',
            'Prénom (Arabic)',
            'E-mail',
            'Banque',
            'Compte bancaire',
        ]);
    }

    public function generatePersonsExcel()
    {
        // NOTE: persons() is paginated. If you want export all rows (not only current page),
        // I can change this to build a non-paginated query for export.
        return $this->generateExcel(fn () => $this->persons()->map(fn ($person) => [
            __("tables.persons.employee_number")   => $person->employee_number,
            __("tables.persons.full_name_fr")      => $person->full_name_fr,
            __("tables.persons.full_name_ar")      => $person->full_name_ar,
            __("tables.persons.email")             => $person->user_email,

            // ✅ NEW export fields
            __("tables.persons.bank_acronym")      => $person->bank_acronym,
            __("tables.persons.bank_account")      => $person->bank_account,

            __("tables.persons.registration_date") => $person->created_at->format('d-m-Y'),
            __("tables.persons.phone")             => $person->phone ?? $person->tel,
            __("tables.persons.card_number")       => $person->card_number,
            __("tables.persons.birth_date")        => $person->birth_date,
            __("tables.persons.birth_place_fr")    => $person->birth_place_fr,
            __("tables.persons.birth_place_ar")    => $person->birth_place_ar,
            __("tables.persons.birth_place_en")    => $person->birth_place_en,
        ])->toArray(), "persons");
    }

    public function render()
    {
        return view('livewire.core.persons-table');
    }
}
