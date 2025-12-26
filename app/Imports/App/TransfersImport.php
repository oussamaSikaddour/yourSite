<?php

namespace App\Imports\App;

use App\Models\Bank;
use App\Models\User;
use App\Models\BankTransfer;
use App\Models\BankingInformation;
use App\Rules\ValidAccountNumber;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TransfersImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    protected array $errors = [];
    protected int $overallLineNumber = 1;
    protected array $transfers = [];
    protected array $bankingInfos = [];
    protected array $banksCache = [];
    protected int $globalTransferId;

    public function __construct(int $globalTransferId)
    {
        $this->globalTransferId = $globalTransferId;
        $this->banksCache = $this->loadBanks();
        $this->bankingInfos = $this->loadActiveBankingInfos();
    }

    /** -----------------------------------------------------------------
     *  Main entry
     *  ----------------------------------------------------------------- */
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $lineNumber = $this->overallLineNumber + $index + 1;
            $cleanRow = $this->sanitizeRow($row->toArray());
            $this->processRow($cleanRow, $lineNumber);
        }

        $this->overallLineNumber += $rows->count();
        $this->finalizeBulkInsert();

        if ($this->hasErrors()) {
            throw new \Exception($this->getFormattedErrors());
        }
    }

    /** -----------------------------------------------------------------
     *  Data Loaders
     *  ----------------------------------------------------------------- */

    protected function loadBanks(): array
    {
        return Bank::pluck('acronym')->toArray();
    }

    protected function loadActiveBankingInfos(): array
    {
        return BankingInformation::with('bankable:id,name_fr')
            ->where('bankable_type', User::class)
            ->where('is_active', true)
            ->get()
            ->mapWithKeys(function ($info) {
                return [
                    $info->account_number => [
                        'user_id' => $info->bankable_id,
                        'name_fr' => $info->bankable->name_fr ?? null,
                        'account_number' => $info->account_number,
                    ],
                ];
            })
            ->toArray();
    }

    /** -----------------------------------------------------------------
     *  Row Handling
     *  ----------------------------------------------------------------- */

    protected function sanitizeRow(array $row): array
    {
        return array_map(fn($value) => is_string($value) ? trim($value) : $value, $row);
    }

    protected function processRow(array $row, int $lineNumber): void
    {
        $validator = $this->getValidator($row);

        if ($validator->fails()) {
            $this->addValidationErrors($validator->errors()->all(), $lineNumber);
            return;
        }

        $this->prepareDataForBulkInsert($row, $lineNumber);
    }

    /** -----------------------------------------------------------------
     *  Validation
     *  ----------------------------------------------------------------- */

    protected function getValidator(array $row)
    {
        $row['montant'] = $row['montant'] ?? "0.00";

        return Validator::make($row, [
            'nom' => 'required|string|min:3|max:100',
            'prenom' => 'required|string|min:3|max:100',
            'banque' => [
                'required',
                'string',
                fn($attribute, $value, $fail) => $this->validateBank($value, $fail),
            ],
            'compte_bancaire' => [
                'required',
                'string',
                new ValidAccountNumber(),
                fn($attribute, $value, $fail) => $this->validateAccount($value, $row, $fail),
            ],
            'montant' => 'required|numeric|min:0.00|max:9999999999999.99',
        ]);
    }

    protected function validateBank(string $value, $fail): void
    {
        if (!in_array($value, $this->banksCache)) {
            $fail(__("imports.bank.acronym.not-found", ['acronym' => $value]));
        }
    }

    protected function validateAccount(string $accountNumber, array $row, $fail): void
    {
        if (!isset($this->bankingInfos[$accountNumber])) {
            $fail(__("imports.banking_information.employee.account-inactive-or-missing", [
                'account' => $accountNumber
            ]));
            return;
        }

        $expectedName = strtolower(trim($this->bankingInfos[$accountNumber]['name_fr']));
        $providedName = strtolower(trim($row['nom'] . ' ' . $row['prenom']));

        if ($expectedName !== $providedName) {
            $fail(__("imports.banking_information.employee.name-mismatch", [
                'expected' => $this->bankingInfos[$accountNumber]['name_fr'],
                'provided' => $row['nom'] . ' ' . $row['prenom'],
            ]));
        }
    }

    /** -----------------------------------------------------------------
     *  Insert or Update Logic
     *  ----------------------------------------------------------------- */

    protected function prepareDataForBulkInsert(array $row, int $lineNumber): void
    {
        $row['montant'] = $row['montant'] ?? "0.00";
        $amount = (float) $row['montant'];

        $accountData = $this->bankingInfos[$row['compte_bancaire']] ?? null;
        if (!$accountData) {
            $this->addValidationErrors(
                [__("imports.banking_information.user_not_found", ['account' => $row['compte_bancaire']])],
                $lineNumber
            );
            return;
        }

        $userId = $accountData['user_id'];

        // ✅ Check for existing transfer for same user + global transfer
        $existingTransfer = BankTransfer::where('global_bank_transfer_id', $this->globalTransferId)
            ->where('user_id', $userId)
            ->first();

        if ($existingTransfer) {
            // ✅ Update the amount (add or replace)
            $existingTransfer->update([
                'amount' => $existingTransfer->amount + $amount, // or use $amount to replace
            ]);
        } else {
            // ✅ Add to bulk insert queue
            $this->transfers[] = [
                'amount' => $amount,
                'user_id' => $userId,
                'global_bank_transfer_id' => $this->globalTransferId,
            ];
        }
    }

    protected function finalizeBulkInsert(): void
    {
        if (!empty($this->transfers)) {
            BankTransfer::insert($this->transfers);
        }

        $this->transfers = [];
    }

    /** -----------------------------------------------------------------
     *  Error Handling
     *  ----------------------------------------------------------------- */

    protected function addValidationErrors(array $errors, int $lineNumber): void
    {
        foreach ($errors as $error) {
            $linePrefix = __("imports.line_number.error", ['number' => $lineNumber]);
            $this->errors[] = "{$linePrefix} : {$error}";
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

    /** -----------------------------------------------------------------
     *  Chunk Configuration
     *  ----------------------------------------------------------------- */

    public function chunkSize(): int
    {
        return 1000;
    }
}
