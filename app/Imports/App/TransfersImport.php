<?php

namespace App\Imports\App;

use App\Models\Bank;
use App\Models\BankTransfer;
use App\Models\BankingInformation;
use App\Models\Person;
use App\Rules\Core\ValidAccountNumber;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TransfersImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    protected array $errors = [];
    protected int $overallLineNumber = 1;

    // Cache
    protected array $banksCache = [];
    protected array $bankingInfos = [];

    // Insert buffer
    protected array $transfersBuffer = [];

    public function __construct(protected int $globalTransferId)
    {
        $this->banksCache = Bank::pluck('acronym')->toArray();

        $this->bankingInfos = BankingInformation::with('bankable:id,last_name_fr,first_name_fr')
            ->where('bankable_type', Person::class)
            ->where('is_active', true)
            ->get()
            ->mapWithKeys(fn($info) => [
                $info->account_number => [
                    'person_id'        => $info->bankable_id,
                    'name_fr'        => $info->bankable->last_name_fr.' '.$info->bankable->first_name_fr ?? null,
                    'account_number' => $info->account_number,
                ]
            ])
            ->toArray();
    }

    /** --------------------------------------------------------------
     *  MAIN ENTRY
     * --------------------------------------------------------------*/
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

    /** --------------------------------------------------------------
     *  HELPERS
     * --------------------------------------------------------------*/
    protected function trimRowValues(array $row): array
    {
        return array_map(
            fn($value) =>
            is_string($value) ? trim($value) : $value,
            $row
        );
    }

    /** --------------------------------------------------------------
     *  ROW PROCESS
     * --------------------------------------------------------------*/
    protected function processRow(array $row, int $lineNumber): void
    {
        $validator = $this->getValidator($row);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->errors[] =
                    __('imports.line_number_error', ['number' => $lineNumber]) .
                    " : " .
                    $error;
            }
            return;
        }

        $this->prepareDataForBulkInsert($row, $lineNumber);
    }

    /** --------------------------------------------------------------
     *  VALIDATION
     * --------------------------------------------------------------*/
    protected function getValidator(array $row)
    {
        $row['montant'] = $row['montant'] ?? "0.00";

        return Validator::make(
            $row,
            [
                'nom'             => 'required|string|min:3|max:100',
                'prenom'          => 'required|string|min:3|max:100',

                'banque'          => [
                    'required',
                    'string',
                    fn($attribute, $value, $fail) =>
                    $this->validateBank($value, $fail),
                ],

                'compte_bancaire' => [
                    'required',
                    'string',
                    new ValidAccountNumber(),
                    fn($attribute, $value, $fail) =>
                    $this->validateAccountOwner($value, $row, $fail),
                ],

                'montant'         => 'required|numeric|min:0|max:9999999999999.99',
            ],
            [],
            [
                'nom' => __('imports.banking_information.last_name'),
                'prenom' => __('imports.banking_information.first_name'),
                'banque' => __('imports.banking_information.bank'),
                'compte_bancaire'     => __('imports.banking_information.account'),
                'montant'     => __('imports.banking_information.amount'),
            ]
        );
    }

    protected function validateBank(string $acronym, $fail): void
    {
        if (!in_array($acronym, $this->banksCache, true)) {
            $fail(__("imports.bank.acronym.not-found", [
                'acronym' => $acronym
            ]));
        }
    }

    protected function validateAccountOwner(string $account, array $row, $fail): void
    {
        if (!isset($this->bankingInfos[$account])) {
            $fail(__("imports.banking_information.employee.account-inactive-or-missing", [
                'account' => $account
            ]));
            return;
        }

        $expected = strtolower(trim($this->bankingInfos[$account]['name_fr']));
        $provided = strtolower(trim($row['nom'] . ' ' . $row['prenom']));

        if ($expected !== $provided) {
            $fail(__("imports.banking_information.employee.name-mismatch", [
                'expected' => $this->bankingInfos[$account]['name_fr'],
                'provided' => $row['nom'] . ' ' . $row['prenom'],
            ]));
        }
    }

    /** --------------------------------------------------------------
     *  INSERT / UPDATE LOGIC
     * --------------------------------------------------------------*/
    protected function prepareDataForBulkInsert(array $row, int $lineNumber): void
    {
        $account = $row['compte_bancaire'];
        $amount  = (float) ($row['montant'] ?? 0);

        if (!isset($this->bankingInfos[$account])) {
            $this->errors[] = __("imports.banking_information.person_not_found", [
                'account' => $account
            ]);
            return;
        }

        $personId = $this->bankingInfos[$account]['person_id'];

        // Merge duplicate transfers inside same import file
        $this->transfersBuffer[] = [
            'person_id'                 => $personId,
            'global_bank_transfer_id' => $this->globalTransferId,
            'amount'                  => $amount,
        ];
    }

    protected function finalizeBulkInsert(): void
    {
        if (empty($this->transfersBuffer)) {
            return;
        }

        DB::transaction(function () {
            foreach ($this->transfersBuffer as $transfer) {

                $existing = BankTransfer::where('global_bank_transfer_id', $transfer['global_bank_transfer_id'])
                    ->where('person_id', $transfer['person_id'])
                    ->first();

                if ($existing) {
                    $existing->increment('amount', $transfer['amount']);
                } else {
                    BankTransfer::create($transfer);
                }
            }
        });

        $this->transfersBuffer = [];
    }

    /** --------------------------------------------------------------
     *  ERRORS
     * --------------------------------------------------------------*/
    protected function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    protected function getFormattedErrors(): string
    {
        return implode("\n", $this->errors);
    }

    /** --------------------------------------------------------------
     *  CHUNK SIZE
     * --------------------------------------------------------------*/
    public function chunkSize(): int
    {
        return 1000;
    }
}
