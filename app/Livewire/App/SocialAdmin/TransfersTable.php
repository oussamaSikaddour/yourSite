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
    use WithPagination, WithFileUploads;
    use TableTrait, GeneralTrait, TextAndPdfTrait, ResponseTrait, AppTrait;

    /* --------------------------------------------------------------------------
     | URL Filters
     * --------------------------------------------------------------------------*/

    #[Url] public $fullName = "";
    #[Url] public $account = "";
    #[Url] public $bank = "";

    /* --------------------------------------------------------------------------
     | State
     * --------------------------------------------------------------------------*/

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

    public ?array $exportCache = null;

    protected array $filterable = ['fullName', 'account', 'bank'];

    protected array $validationRules = [
        'fullName' => ['nullable', 'string'],
        'account'  => ['nullable', 'string'],
        'bank'     => ['nullable', 'string'],
    ];

    /* --------------------------------------------------------------------------
     | Helpers
     * --------------------------------------------------------------------------*/

    protected function getFilteredTransferIds(): array
    {
        return (clone $this->prepareTransfers())
            ->select('bank_transfers.id')
            ->pluck('bank_transfers.id')
            ->unique()
            ->values()
            ->toArray();
    }

    protected function getFilteredTransfersCount(): int
    {
        return count($this->getFilteredTransferIds());
    }

    /* --------------------------------------------------------------------------
     | Bulk Dialogs
     * --------------------------------------------------------------------------*/

    public function openDeleteBulkDialog(): void
    {
        $count = $this->getFilteredTransfersCount();

        if ($count === 0) {
            $this->dispatch('open-errors', [__('tables.transfers.errors.empty')]);
            return;
        }

        $data = [
            "question" => "dialogs.title.transfers",
            "details" => ["transfers", $count],
            "actionEvent" => [
                "event" => "delete-bulk-transfers",
                "parameters" => [],
            ],
        ];

        $this->dispatch("open-dialog", $data);
    }

    public function openEmptyAmountBulkDialog(): void
    {
        $count = $this->getFilteredTransfersCount();

        if ($count === 0) {
            $this->dispatch('open-errors', [__('tables.transfers.errors.empty')]);
            return;
        }

        $data = [
            "question" => "dialogs.title.empty_transfers",
            "details" => ["empty_transfers", $count],
            "actionEvent" => [
                "event" => "empty-amount-bulk-transfers",
                "parameters" => [],
            ],
        ];

        $this->dispatch("open-dialog", $data);
    }

    /* --------------------------------------------------------------------------
     | Bulk Actions
     * --------------------------------------------------------------------------*/

    #[On("delete-bulk-transfers")]
    public function deleteBulkTransfers(): void
    {
        try {
            $ids = $this->getFilteredTransferIds();

            if (empty($ids)) {
                $this->dispatch('open-errors', [__('tables.transfers.errors.empty')]);
                return;
            }

            BankTransfer::whereIn('id', $ids)->delete();

            $this->exportCache = null;
            $this->resetPage();

            $this->dispatch('open-toast', __('tables.transfers.success.delete'));
            $this->dispatch('update-transfers-table');
        } catch (\Exception $e) {
            Log::error('Error deleting bulk transfers: ' . $e->getMessage());
            $this->dispatch('open-errors', [__('forms.common.errors.default')]);
        }
    }

    #[On("empty-amount-bulk-transfers")]
    public function emptyAmountBulkTransfers(): void
    {
        try {
            $ids = $this->getFilteredTransferIds();

            if (empty($ids)) {
                $this->dispatch('open-errors', [__('tables.transfers.errors.empty')]);
                return;
            }

            BankTransfer::whereIn('id', $ids)->update(['amount' => 0]);

            $this->exportCache = null;

            $this->dispatch('open-toast', __('tables.transfers.success.update'));
            $this->dispatch('update-transfers-table');
        } catch (\Exception $e) {
            Log::error('Error emptying bulk amounts: ' . $e->getMessage());
            $this->dispatch('open-errors', [__('forms.common.errors.default')]);
        }
    }

    /* --------------------------------------------------------------------------
     | Banks
     * --------------------------------------------------------------------------*/

    #[Computed]
    public function banks()
    {
        return Bank::get(['id', 'acronym']);
    }

    /* --------------------------------------------------------------------------
     | Query builder + computed lists
     * --------------------------------------------------------------------------*/

    public function prepareTransfers()
    {
        $isArabic = $this->fullName && preg_match('/\p{Arabic}/u', $this->fullName);

        $local = $this->local === 'ar' ? 'ar' : 'fr';

        $lastNameColumn  = $isArabic ? 'last_name_ar'  : "last_name_{$local}";
        $firstNameColumn = $isArabic ? 'first_name_ar' : "first_name_{$local}";

        $fullNameExpression = DB::raw("CONCAT(COALESCE($lastNameColumn,''), ' ', COALESCE($firstNameColumn,''))");

        return BankTransfer::query()
            ->leftJoin('persons', 'bank_transfers.person_id', '=', 'persons.id')
            ->leftJoin('banking_information', function ($join) {
                $join->on('persons.id', '=', 'banking_information.bankable_id')
                    ->where('banking_information.bankable_type', Person::class)
                    ->where('banking_information.is_active', true);
            })
            ->leftJoin('banks', 'banking_information.bank_id', '=', 'banks.id')
            ->when($this->fullName, function ($q) use ($fullNameExpression) {
                $q->where($fullNameExpression, 'like', '%' . $this->fullName . '%');
            })
            ->when($this->account, fn($q) =>
                $q->where('banking_information.account_number', 'like', '%' . $this->account . '%')
            )
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

    /* --------------------------------------------------------------------------
     | Mount
     * --------------------------------------------------------------------------*/

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
            return;
        }

        $this->operationDate = Carbon::parse($this->globalTransfer->date);

        $this->activeAppBankingInfo = BankingInformation::with(['bank:id,code', 'bankable:id,address_fr'])
            ->where('bankable_type', GeneralSetting::class)
            ->where("is_active", true)
            ->first();

        if (!$this->activeAppBankingInfo) {
            $this->dispatch(
                'active-establishment-banking-info-not-found',
                __('tables.transfers.errors.not_found.active_establishment_banking_info')
            );
        }
    }

    #[On("active-establishment-banking-info-not-found")]
    public function dispatchError($e): void
    {
        $this->dispatch('open-errors', $e);
    }

    /* --------------------------------------------------------------------------
     | Filters
     * --------------------------------------------------------------------------*/

    public function resetFilters(): void
    {
        $this->fullName = "";
        $this->account = "";
        $this->bank = "";
        $this->resetPage();
        $this->exportCache = null;
    }

    /* --------------------------------------------------------------------------
     | Single delete dialog + action
     * --------------------------------------------------------------------------*/

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

    #[On("delete-transfer")]
    public function deleteTransfer($transfer): void
    {
        try {
            $id = data_get($transfer, 'id', $transfer);

            BankTransfer::whereKey($id)->delete();

            $this->exportCache = null;

            $this->dispatch('open-toast', __('tables.transfers.success.delete'));
            $this->dispatch('update-transfers-table');
        } catch (\Exception $e) {
            Log::error('Error in deleteTransfer method: ' . $e->getMessage());
            $this->dispatch('open-errors', [__('forms.common.errors.default')]);
        }
    }

    /* --------------------------------------------------------------------------
     | Bonuses
     * --------------------------------------------------------------------------*/

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

        $count = $this->getFilteredTransfersCount();

        if ($count === 0) {
            $this->dispatch('open-errors', [__('tables.transfers.errors.empty')]);
            return;
        }

        $data = [
            'question' => 'dialogs.title.add_bonuses',
            'details' => ['add_bonuses', $this->sameAmount],
            'actionEvent' => [
                'event' => 'add-bonuses-to-all-transfers',
                'parameters' => [
                    'sameAmount' => $this->sameAmount,
                ],
            ],
        ];

        $this->dispatch("open-dialog", $data);
    }

    #[On("add-bonuses-to-all-transfers")]
    public function addBonusesToAllTransfers($payload): void
    {
        try {
            $sameAmount = (float) data_get($payload, 'sameAmount', 0);

            if ($sameAmount == 0) {
                $this->dispatch('open-errors', [__('tables.transfers.errors.bonuses.not_set')]);
                return;
            }

            $ids = $this->getFilteredTransferIds();

            if (empty($ids)) {
                $this->dispatch('open-errors', [__('tables.transfers.errors.empty')]);
                return;
            }

            BankTransfer::whereIn('id', $ids)
                ->update(['amount' => DB::raw("amount + {$sameAmount}")]);

            $this->dialogOpen = false;
            $this->exportCache = null;

            $this->dispatch('open-toast', __('tables.transfers.success.bonus.add'));
            $this->dispatch('update-transfers-table');
        } catch (\Exception $e) {
            Log::error('Error adding bonuses to transfers: ' . $e->getMessage());
            $this->dispatch('open-errors', [__('forms.common.errors.default')]);
        }
    }

    /* --------------------------------------------------------------------------
     | File download (errors)
     * --------------------------------------------------------------------------*/

    #[On('errors-file-data')]
    public function downloadUsersErrorsTextFile($errorsFileData)
    {
        return $this->streamFileDownload($errorsFileData['filePath'], $errorsFileData['fileName']);
    }

    /* --------------------------------------------------------------------------
     | EDI / PDF Slip
     * --------------------------------------------------------------------------*/

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

    /* --------------------------------------------------------------------------
     | Livewire updated hook
     * --------------------------------------------------------------------------*/

    public function updated(string $property): void
    {
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

    /* --------------------------------------------------------------------------
     | Render
     * --------------------------------------------------------------------------*/

    public function render()
    {
        return view('livewire.app.social-admin.transfers-table');
    }

    /* --------------------------------------------------------------------------
     | EDI internals
     * --------------------------------------------------------------------------*/

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
