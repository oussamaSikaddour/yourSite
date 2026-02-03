<?php

namespace App\Livewire\App\SocialAdmin;

use App\Models\Bank;
use App\Models\BankingInformation;
use App\Models\BankTransfer;
use App\Models\GeneralSetting;
use App\Models\GlobalBankTransfer;
use App\Models\Person;
use App\Traits\App\Common\AppTrait;
use App\Traits\Core\Common\GeneralTrait;
use App\Traits\Core\Common\TableTrait;
use App\Traits\Core\Common\TextAndPdfTrait;
use App\Traits\Core\Web\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class TransfersTable extends Component
{
    use WithPagination, WithFileUploads, TableTrait, GeneralTrait, TextAndPdfTrait, ResponseTrait, AppTrait;

    #[Url]
    public $fullName = "";
    #[Url]
    public $account = "";
    #[Url]
    public $bank = "";

    public bool $selectAll = false;
    public array $selectedValues = [];
    public $banksOptions = [];
    public $globalTransferId;
    public $sameAmount;
    public $motive;
    public $activeAppBankingInfo;
    public $globalTransfer;
    public $operationDate;
    public $dialogOpen = false;
    public $local = "fr";
    public $reference = "001";

    // Cache for export to avoid repeated queries in same request
    public ?array $exportCache = null;

    protected array $filterable = ['fullName', 'account', 'bank'];
    protected array $validationRules = [
        'fullName' => ['nullable', 'string'],
        'account'  => ['nullable', 'string'],
        'bank'     => ['nullable', 'string'],
    ];

    /**
     * Clear selection when user changes pagination page
     * (keeps "Select All = current page only" consistent)
     */
    public function updatedPage(): void
    {
        $this->selectedValues = [];
        $this->selectAll = false;
    }

    public function updatedSelectAll($value): void
    {
        // Select ALL ids from CURRENT PAGE only
        if ((bool) $value === true) {
            $idsOnPage = $this->transfers->getCollection()->pluck('id')->all();
            $this->selectedValues = $idsOnPage;
        } else {
            $this->selectedValues = [];
        }
    }

    public function updatedSelectedValues(): void
    {
        // Keep selectAll synced with what is selected on CURRENT PAGE
        $idsOnPage = $this->transfers->getCollection()->pluck('id')->all();

        if (count($idsOnPage) === 0) {
            $this->selectAll = false;
            return;
        }

        // If every id on the page is in selectedValues => selectAll true
        $this->selectAll = empty(array_diff($idsOnPage, $this->selectedValues));
    }

    public function openDeleteBulkDialog(): void
    {
        if (empty($this->selectedValues)) {
            $this->dispatch('open-errors', [__('tables.transfers.errors.no_selection')]);
            return;
        }

        $data = [
            "question" => "dialogs.title.transfers",
            "details" => ["transfers", count($this->selectedValues)],
            "actionEvent" => [
                "event" => "delete-bulk-transfers",
                "parameters" => $this->selectedValues,
            ],
        ];

        $this->dispatch("open-dialog", $data);
    }

    public function openEmptyAmountBulkDialog(): void
    {
        if (empty($this->selectedValues)) {
            $this->dispatch('open-errors', [__('tables.transfers.errors.selectedValues')]);
            return;
        }

        $data = [
            "question" => "dialogs.title.empty_transfers",
            "details" => ["empty_transfers", count($this->selectedValues)],
            "actionEvent" => [
                "event" => "empty-amount-bulk-transfers",
                "parameters" => $this->selectedValues,
            ],
        ];

        $this->dispatch("open-dialog", $data);
    }

    #[On("delete-bulk-transfers")]
    public function deleteBulkTransfers($ids): void
    {
        try {
            $ids = is_array($ids) ? $ids : [];

            if (empty($ids)) {
                $this->dispatch('open-errors', [__('tables.transfers.errors.empty')]);
                return;
            }

            BankTransfer::whereIn('id', $ids)->delete();

            $this->selectedValues = [];
            $this->selectAll = false;

            $this->dispatch('open-toast', __('tables.transfers.success.delete'));
            $this->dispatch('update-transfers-table');
        } catch (\Exception $e) {
            Log::error('Error deleting bulk transfers: ' . $e->getMessage());
            $this->dispatch('open-errors', [__('forms.common.errors.default')]);
        }
    }

    #[On("empty-amount-bulk-transfers")]
    public function emptyAmountBulkTransfers($ids): void
    {
        try {
            $ids = is_array($ids) ? $ids : [];

            if (empty($ids)) {
                $this->dispatch('open-errors', [__('tables.transfers.errors.empty')]);
                return;
            }

            // IMPORTANT: decide what you want:
            // 1) If "empty" means NULL, use null
            // 2) If your DB column is not nullable, keep 0
            BankTransfer::whereIn('id', $ids)->update(['amount' => 0]);

            $this->selectedValues = [];
            $this->selectAll = false;

            $this->dispatch('open-toast', __('tables.transfers.success.update'));
            $this->dispatch('update-transfers-table');
        } catch (\Exception $e) {
            Log::error('Error emptying bulk amounts: ' . $e->getMessage());
            $this->dispatch('open-errors', [__('forms.common.errors.default')]);
        }
    }

    #[Computed]
    public function banks()
    {
        return Bank::get(['id', 'acronym']);
    }

    public function prepareTransfers()
    {
        // Detect Arabic input if any
        $isArabic = $this->fullName && preg_match('/\p{Arabic}/u', $this->fullName);

        // Resolve locale
        $local = $this->local === 'ar' ? 'ar' : 'fr';

        // Choose name columns
        $lastNameColumn  = $isArabic ? 'last_name_ar'  : "last_name_{$local}";
        $firstNameColumn = $isArabic ? 'first_name_ar' : "first_name_{$local}";

        // Use COALESCE to prevent CONCAT returning NULL
        $fullNameExpression = DB::raw("CONCAT(COALESCE($lastNameColumn,''), ' ', COALESCE($firstNameColumn,''))");

        $query = BankTransfer::query()
            ->leftJoin('persons', 'bank_transfers.person_id', '=', 'persons.id')
            ->leftJoin('banking_information', function ($join) {
                $join->on('persons.id', '=', 'banking_information.bankable_id')
                    ->where('banking_information.bankable_type', Person::class)
                    ->where('banking_information.is_active', true);
            })
            ->leftJoin('banks', 'banking_information.bank_id', '=', 'banks.id')

            // Full name filter
            ->when($this->fullName, function ($q) use ($fullNameExpression) {
                $q->where($fullNameExpression, 'like', '%' . $this->fullName . '%');
            })

            // Account filter
            ->when($this->account, fn($q) =>
                $q->where('banking_information.account_number', 'like', '%' . $this->account . '%')
            )

            // Bank filter
            ->when($this->bank, fn($q) =>
                $q->where('banks.id', $this->bank)
            )

            ->where('bank_transfers.global_bank_transfer_id', $this->globalTransferId)

            ->select(
                'bank_transfers.*',
                DB::raw("CONCAT(COALESCE($lastNameColumn,''), ' ', COALESCE($firstNameColumn,'')) as beneficiary"),
                'banking_information.account_number as account',
                'banks.acronym as bank'
            );

        return $query;
    }

    #[Computed]
    public function transfers()
    {
        $query = $this->prepareTransfers();

        return $query->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    #[Computed]
    public function transfersToExport()
    {
        if ($this->exportCache) {
            return $this->exportCache;
        }

        $query = $this->prepareTransfers();

        $this->exportCache = [
            'total_count'  => (clone $query)->count(),
            'total_amount' => (clone $query)->sum('bank_transfers.amount'),
            'results'      => $query->get(),
        ];

        return $this->exportCache;
    }

    public function mount()
    {
        $this->local = app()->getLocale();

        $this->banksOptions = $this->populateSelectorOption(
            $this->banks(),
            'id',
            'acronym',
            __('selectors.default.banks')
        );

        $this->globalTransfer = GlobalBankTransfer::find($this->globalTransferId);
        if (!$this->globalTransfer) {
            $this->dispatch('open-errors', [__("tables.transfers.errors.nopt_found.global_transfer")]);
        }

        $this->operationDate = Carbon::parse($this->globalTransfer->date);

        $this->activeAppBankingInfo = BankingInformation::with(['bank:id,code', 'bankable:id,address_fr'])
            ->where('bankable_type', GeneralSetting::class)
            ->where("is_active", true)
            ->first();

        if (!$this->activeAppBankingInfo) {
            $this->dispatch('active-establishment-banking-info-not-found', __('tables.transfers.errors.not_found.active_establishment_banking_info'));
        }
    }

    #[On("active-establishment-banking-info-not-found")]
    public function dispatchError($e): void
    {
        $this->dispatch('open-errors', $e);
    }

    public function resetFilters(): void
    {
        $this->fullName = "";
        $this->account = "";
        $this->bank = "";
        $this->resetPage();

        // Clear selection + export cache
        $this->selectedValues = [];
        $this->selectAll = false;
        $this->exportCache = null;
    }

    public function openDeleteDialog($transfer): void
    {
        $data = [
            "question" => "dialogs.title.transfer",
            "details" => ["transfer", data_get($transfer, 'beneficiary')],
            "actionEvent" => [
                "event" => "delete-transfer",
                "parameters" => $transfer
            ]
        ];

        $this->dispatch("open-dialog", $data);
    }

    /**
     * FIXED:
     * We accept array or ID and delete by key.
     */
    #[On("delete-transfer")]
    public function deleteTransfer($transfer): void
    {
        try {
            $id = data_get($transfer, 'id', $transfer);
            BankTransfer::whereKey($id)->delete();

            $this->dispatch('open-toast', __('tables.transfers.success.delete'));
            $this->dispatch('update-transfers-table');
        } catch (\Exception $e) {
            Log::error('Error in deleteTransfer method: ' . $e->getMessage());
            $this->dispatch('open-errors', [__('forms.common.errors.default')]);
        }
    }

    #[On("selected-bonuses")]
    public function selectBonuses($data): void
    {
        if (is_array($data) && array_sum($data) != 0) {
            $this->sameAmount = array_sum($data);
        } else {
            $this->sameAmount = null;
            $this->dispatch('open-errors', [__('tables.transfers.errors.bonuses.not_selected')]);
        }
    }

    public function openAddBonusesDialog(): void
    {
        $this->dialogOpen = true;

        if (!isset($this->sameAmount)) {
            $this->dispatch('open-errors', [__('tables.transfers.errors.bonuses.not_set')]);
            return;
        }

        if (empty($this->selectedValues)) {
            $this->dispatch('open-errors', [__('tables.transfers.errors.selectedValues')]);
            return;
        }

        $data = [
            'question' => 'dialogs.title.add_bonuses',
            'details' => ['add_bonuses', $this->sameAmount],
            'actionEvent' => [
                'event' => 'add-sameAmount-selected',
                'parameters' => [
                    'sameAmount' => $this->sameAmount,
                    'ids' => $this->selectedValues,
                ],
            ],
        ];

        $this->dispatch("open-dialog", $data);
    }

    /**
     * Improved: single SQL update instead of per-row loop
     * (Requires amount to be numeric/decimal in DB for best results)
     */
    #[On("add-sameAmount-selected")]
    public function addBonusesToSelectedTransfers($payload): void
    {
        try {
            $sameAmount = (float) data_get($payload, 'sameAmount', 0);
            $ids = data_get($payload, 'ids', []);

            if ($sameAmount == 0) {
                $this->dispatch('open-errors', [__('tables.transfers.errors.bonuses.not_set')]);
                return;
            }

            if (!is_array($ids) || empty($ids)) {
                $this->dispatch('open-errors', [__('tables.transfers.errors.bonuses.not_selected')]);
                return;
            }

            BankTransfer::whereIn('id', $ids)
                ->update(['amount' => DB::raw("amount + {$sameAmount}")]);

            $this->selectedValues = [];
            $this->selectAll = false;
            $this->dialogOpen = false;

            $this->dispatch('open-toast', __('tables.transfers.success.bonus.add'));
            $this->dispatch('update-transfers-table');
        } catch (\Exception $e) {
            Log::error('Error adding bonuses to selected transfers: ' . $e->getMessage());
            $this->dispatch('open-errors', [__('forms.common.errors.default')]);
        }
    }

    #[On('errors-file-data')]
    public function downloadUsersErrorsTextFile($errorsFileData)
    {
        return $this->streamFileDownload($errorsFileData['filePath'], $errorsFileData['fileName']);
    }

    public function generateEDI()
    {
        $ediLines = [];
        $ediLines[] = $this->generateHeader();
        $ediLines = array_merge($ediLines, $this->generateBody());
        $ediLines[] = $this->generateFooter();

        $filename = 'VIRM_' . time() . '.txt';
        $textFile = $this->generateTextFile($ediLines, $filename);

        $this->dispatch('print-transfer-slip');
        return $this->streamFileDownload($textFile['filePath'], $textFile['fileName']);
    }

    #[On('print-transfer-slip')]
    public function printTransferSlipPdf()
    {
        $agencyCode = substr($this->activeAppBankingInfo->account_number, 3, 5);
        $accountKey = substr($this->activeAppBankingInfo->account_number, -2);
        $account = substr($this->activeAppBankingInfo->account_number, 0, -2);
        $account = $this->insertSpacesAtPositions($account, ' ', [3, 8]);

        $export = $this->transfersToExport();

        $slipData = [
            "agencyName" => $this->activeAppBankingInfo->agency_fr,
            "agencyCode" => $agencyCode,
            "accountKey" => $accountKey,
            "account" => $account,
            "reference" => $this->reference,
            "date" => date('d/m/Y', strtotime($this->globalTransfer->date)),
            "totalAmount" => $export['total_amount'],
            "numberOperations" => $export['total_count']
        ];

        try {
            return $this->generateAndDownloadPdf("pdfs.transfer-slip", $slipData, 'slip.pdf');
        } catch (\Exception $e) {
            Log::error('Error in printTransferSlipPdf method: ' . $e->getMessage());
            $this->dispatch('open-errors', $e->getMessage());
        }
    }

    public function updated(string $property): void
    {
        // Clear export cache when anything important changes
        if (in_array($property, $this->filterable) || $property === 'perPage') {
            $this->exportCache = null;
        }

        if ($property === "excelFile") {
            $errorsFileData = $this->whenExcelFileUploaded(
                "App\TransfersImport",
                __('tables.transfers.excel.upload-success'),
                parameters: [$this->globalTransferId]
            );

            if (is_array($errorsFileData)) {
                $this->dispatch('errors-file-data', errorsFileData: $errorsFileData);
            }
        }

        if (in_array($property, $this->filterable) || $property === 'perPage') {
            $this->selectedValues = [];
            $this->selectAll = false;
            $this->resetPage();
        }

        if (array_key_exists($property, $this->validationRules)) {
            try {
                $this->validateOnly($property, $this->validationRules);
            } catch (ValidationException $e) {
                $this->dispatch('open-errors', $e->validator->errors()->all());
            }
        }
    }

    public function render()
    {
        return view('livewire.app.social-admin.transfers-table');
    }

    private function generateHeader()
    {
        $iban = "    ";
        $export = $this->transfersToExport();

        $numberOfOperations = str_pad($export['total_count'], 6, '0', STR_PAD_LEFT);

        $totalAmount = number_format((float)$export['total_amount'], 2, '.', '');
        $totalAmount = str_replace(['.', ','], '', $totalAmount);
        $totalAmount = str_pad($totalAmount, 16, '0', STR_PAD_LEFT);

        $headerOfTheDiscount = "VIRM";
        $bankIdentifier = str_pad($this->activeAppBankingInfo->bank->code, 3, '0', STR_PAD_LEFT);
        $natureOfOperation = "010";
        $natureOfFunds = "0";
        $presenceIndicator = "1";
        $rib = str_pad($this->activeAppBankingInfo->account_number, 20, '0', STR_PAD_LEFT);
        $socialReason = str_pad($this->globalTransfer->motive_fr, 50, ' ', STR_PAD_RIGHT);
        $address = str_pad($this->activeAppBankingInfo->bankable->address_fr, 70, ' ', STR_PAD_RIGHT);

        $this->reference = str_pad($this->globalTransfer->reference, 3, '0', STR_PAD_LEFT);

        return strtoupper(str_pad(
            $headerOfTheDiscount . $bankIdentifier . $natureOfOperation . $natureOfFunds . $presenceIndicator . $rib . $iban . $socialReason . $address . $this->operationDate->format('Ymd') . $this->reference . $numberOfOperations . $totalAmount,
            219,
            ' ',
            STR_PAD_RIGHT
        ));
    }

    private function generateBody()
    {
        $body = [];
        $presenceIndicator = "1";
        $address = str_pad(substr($this->activeAppBankingInfo->bankable->address_fr, 0, 71), 70, ' ', STR_PAD_RIGHT);
        $libelle = str_pad($this->globalTransfer->motive_fr, 70, ' ', STR_PAD_RIGHT);
        $month = $this->operationDate->format('m');
        $year = $this->operationDate->format('y');
        $iban = "    ";

        $export = $this->transfersToExport();

        foreach ($export['results'] as $index => $transfer) {
            $lineNumber = str_pad(($index + 1) . $month . $year, 10, '0', STR_PAD_LEFT);
            $rib = $transfer->account;
            $name = str_pad(substr($transfer->beneficiary, 0, 50), 50, ' ', STR_PAD_RIGHT);
            $amount = str_pad(str_replace(['.', ','], '', $transfer->amount), 15, '0', STR_PAD_LEFT);
            $filler = str_pad('', 79, ' ', STR_PAD_RIGHT);

            $body[] = strtoupper($lineNumber . $presenceIndicator . $rib . $iban . $name . $address . $amount . $libelle . $filler);
        }

        return $body;
    }

    private function generateFooter()
    {
        return str_pad("FVIR", 99, ' ', STR_PAD_RIGHT);
    }
}
