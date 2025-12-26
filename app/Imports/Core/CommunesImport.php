<?php

namespace App\Imports\Core;

use App\Models\Daira;
use App\Models\FieldSpecialty;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CommunesImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    /** @var array Accumulated validation errors with line numbers */
    protected array $errors = [];

    /** @var int Tracks current overall line number across chunks */
    protected int $overallLineNumber = 1;

    protected ?int $dairaId=null;


        public function __construct($dairaId)
    {
        $this->dairaId =$dairaId;
    }
    /**
     * Process each chunk of rows.
     */
    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $lineNumber = $this->overallLineNumber + $index + 1;

            $cleanRow = $this->trimRowValues($row->toArray());
            $this->processRow($cleanRow, $lineNumber);
        }

        $this->overallLineNumber += $rows->count();

        if ($this->hasErrors()) {
            throw new \Exception($this->getFormattedErrors());
        }
    }

    /**
     * Trim all string values in a row.
     */
    protected function trimRowValues(array $row): array
    {
        return array_map(fn ($value) => is_string($value) ? trim($value) : $value, $row);
    }

    /**
     * Validate and insert a single row.
     */
    protected function processRow(array $row, int $lineNumber): void
    {
        $validator = $this->getValidator($row);

        if ($validator->fails()) {
            $this->addValidationErrors($validator->errors()->all(), $lineNumber);
            return;
        }

        $this->insertCommune($row);
    }

    /**
     * Build the validator with rules.
     */
    protected function getValidator(array $row)
    {
        $unique = fn ($column) =>
            Rule::unique('communes', $column)->whereNull('deleted_at');

        return Validator::make($row, [
            'code'                => ['required', 'string', 'max:10', $unique('code')],
            'designation_francais'   => ['required', 'string', 'min:3', 'max:255', $unique('designation_fr')],
            'designation_anglais'    => ['required', 'string', 'min:3', 'max:255', $unique('designation_en')],
            'designation_arabe'     => ['required', 'string', 'min:3', 'max:255', $unique('designation_ar')],
        ],[],[
            'code'                => __('imports.municipalities.code'),
            'designation_francais'   =>__('imports.municipalities.designation_fr'),
            'designation_anglais'    => __('imports.municipalities.designation_en'),
            'designation_arabe'     => __('imports.municipalities.designation_ar'),
        ]);
    }

    /**
     * Insert field into the database.
     */
    protected function insertCommune(array $row): void
    {
        Daira::create([
            'code'          => $row['code'],
            'designation_fr'   => $row['designation_francais'],
            'designation_en'   => $row['designation_anglais'],
            'designation_ar'   => $row['designation_arabe'],
            'daira_id'=>$this->dairaId
        ]);
    }

    /**
     * Collect validation errors with line number.
     */
    protected function addValidationErrors(array $errors, int $lineNumber): void
    {
        foreach ($errors as $error) {
            $this->errors[] = __("imports.line_number_error", ['number' => $lineNumber]) . " : " . $error;
        }
    }

    /**
     * Check if there are any collected errors.
     */
    protected function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Combine all error messages into a single string.
     */
    protected function getFormattedErrors(): string
    {
        return implode("\n", $this->errors);
    }

    /**
     * Define chunk size for processing Excel rows.
     */
    public function chunkSize(): int
    {
        return 1000;
    }
}
