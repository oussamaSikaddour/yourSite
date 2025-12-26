<?php

namespace App\Livewire\Default\SocialAdmin;

use App\Models\Bank;
use App\Models\BankingInformation;
use App\Models\BankTransfer;
use App\Models\GeneralSetting;
use App\Models\GlobalBankTransfer;
use App\Models\User;
use App\Traits\App\Common\AppTrait;
use App\Traits\Core\Common\GeneralTrait;
use App\Traits\Core\Common\TableTrait;
use App\Traits\Core\Common\TextAndPdfTrait;
use App\Traits\Core\Web\ResponseTrait;
use Carbon\Carbon;
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
    use WithPagination, WithFileUploads, TableTrait, GeneralTrait, TextAndPdfTrait, ResponseTrait,AppTrait;

    #[Url]
    public $fullName = "";
    #[Url]
    public $account = "";
    #[Url]
    public $bank = "";

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

    protected array $filterable = ['fullName', 'account', 'bank'];
    protected array $validationRules = [
        'fullName' => ['nullable', 'string'],
        'account' => ['nullable', 'string'],
        'bank' => ['nullable', 'string'],
    ];

    #[Computed]
    public function banks()
    {
        return Bank::get(['id', 'acronym']);
    }

    public function prepareTransfers()
    {
        $query = BankTransfer::query()
            ->leftJoin('users', 'bank_transfers.user_id', '=', 'users.id')
            ->leftJoin('banking_information', function ($join) {
                $join->on('users.id', '=', 'banking_information.bankable_id')
                    ->where('banking_information.bankable_type', User::class)
                    ->where('banking_information.is_active', true);
            })
            ->leftJoin('banks', 'banking_information.bank_id', '=', 'banks.id')
            ->when($this->fullName, fn($q) => $q->where('users.name_fr', 'like', "%{$this->fullName}%"))
            ->when($this->account, fn($q) => $q->where('banking_information.account_number', 'like', "%{$this->account}%"))
            ->when($this->bank, fn($q) => $q->where('banks.id', $this->bank))
            ->where('bank_transfers.global_bank_transfer_id', $this->globalTransferId)
            ->select(
                'bank_transfers.*',
                'users.name_fr as beneficiary',
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
        $query = $this->prepareTransfers();
        return [
            'total_count' => $query->count(),
            'total_amount' => $query->sum('bank_transfers.amount'),
            'results' => $query->get(),
        ];
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
            $this->dispatch('open-errors', __("tables.transfers.errors.nopt_found.global_transfer"));
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
    public function dispatchError($e)
    {
        $this->dispatch('open-errors', $e);
    }

    public function resetFilters()
    {
        $this->fullName = "";
        $this->account = "";
        $this->bank = "";
        $this->resetPage();
    }

    public function openDeleteDialog($transfer)
    {
        $data = [
            "question" => "dialogs.title.transfer",
            "details" => ["transfer", $transfer['beneficiary']],
            "actionEvent" => [
                "event" => "delete-transfer",
                "parameters" => $transfer
            ]
        ];
        $this->dispatch("open-dialog", $data);
    }

    #[On("delete-transfer")]
    public function deleteTransfer(BankTransfer $globalTransfer)
    {
        try {
            $globalTransfer->delete();
        } catch (\Exception $e) {
            Log::error('Error in deleteGlobalTransfer method: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }

    #[On("selected-bonuses")]
    public function selectBonuses($data)
    {
        if (is_array($data) && array_sum($data) != 0) {
            $this->sameAmount = array_sum($data);

        } else {
            $this->sameAmount=null;
            $this->dispatch('open-errors', [__('tables.transfers.errors.bonuses.not_selected')]);
        }
    }

    public function openAddBonusesDialog()
    {
       $this->dialogOpen = true;
        if (!isset($this->sameAmount)) {
            $this->dispatch('open-errors', [__('tables.transfers.errors.bonuses.not_set')]);
            return;
        }
        if ($this->transfersToExport()['total_count'] == 0) {
            $this->dispatch('open-toast', __('tables.transfers.errors.empty'));
            return;
        }

        $data = [
            'question' => 'dialogs.title.add_bonuses',
            'details' => ['add_bonuses', $this->sameAmount],
            'actionEvent' => [
                'event' => 'add-sameAmount',
                'parameters' => $this->sameAmount
            ]
        ];
        $this->dispatch("open-dialog", $data);
    }

    #[On("add-sameAmount")]
    public function addBonusesToAllEmployees($sameAmount)
    {
        $transfers = $this->transfersToExport()['results'];

        foreach ($transfers as $transfer) {
            $newAmount = number_format($transfer->amount + (float)$sameAmount, 2, '.', '');
            $transfer->update(['amount' => $newAmount]);
        }

        $this->dispatch('open-toast', __('tables.transfers.success.bonus.add'));
        $this->dispatch('update-transfers-table');
        $this->dialogOpen = false;
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

        $slipData = [
            "agencyName" => $this->activeAppBankingInfo->agency_fr,
            "agencyCode" => $agencyCode,
            "accountKey" => $accountKey,
            "account" => $account,
            "reference" => $this->reference,
            "date" => date('d/m/Y', strtotime($this->globalTransfer->date)),
            "totalAmount" => $this->transfersToExport()['total_amount'],
            "numberOperations" => $this->transfersToExport()['total_count']
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
        if ($property === "excelFile") {
            $errorsFileData = $this->whenExcelFileUploaded("TransfersImport", __('tables.transfers.excel.upload-success'), parameters: [$this->globalTransferId]);
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

    public function render()
    {
        return view('livewire.default.social-admin.transfers-table');
    }

    private function generateHeader()
    {
        $iban = "    ";
        $numberOfOperations = str_pad($this->transfersToExport()['total_count'], 6, '0', STR_PAD_LEFT);
        $totalAmount = number_format((float)$this->transfersToExport()['total_amount'], 2, '.', '');
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

        foreach ($this->transfersToExport()['results'] as $index => $transfer) {
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
