<?php

namespace App\Imports\App;

use App\Models\FieldSpecialty;
use App\Models\Person;
use App\Models\Service;
use App\Models\User;
use App\Rules\Core\LandLineNumberExist;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ServicesImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    protected array $errors = [];
    protected int $overallLineNumber = 1;
    protected array $services = [];
    protected array $specialtiesCache = [];

    protected array $personnelCache = [];


    public function __construct()
    {

        // specialty_name => id
        $this->specialtiesCache = FieldSpecialty::pluck('id', 'designation_fr')->mapWithKeys(function ($id, $name) {
            return [Str::lower(trim($name)) => $id];
        })->toArray();
        // localized service types

        // personnel name_fr => id (scoped to establishment)
        $this->personnelCache = Person::select(
            'id',
            DB::raw("LOWER(CONCAT(last_name_fr, ' ', first_name_fr)) as full_name")
        )
            ->pluck('id', 'full_name')
            ->toArray();
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $lineNumber = $this->overallLineNumber + $index + 1;
            $cleanRow   = $this->trimRowValues($row->toArray());
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
        return array_map(
            fn($value) => is_string($value) ? mb_strtolower(trim($value)) : $value,
            $row
        );
    }

    protected function processRow(array $row, int $lineNumber): void
    {
        $validator = $this->getValidator($row);

        if ($validator->fails()) {
            $this->addValidationErrors($validator->errors()->all(), $lineNumber);
            return;
        }

        $this->prepareDataForBulkInsert($row);
    }

    protected function getValidator(array $row)
    {
        $uniqueRules = fn(string $column) => [
            'required',
            'string',
            'min:5',
            'max:255',
            Rule::unique('services', $column)
                ->whereNull('deleted_at'),
        ];

        return Validator::make(
            $row,
            [
                'nom_arabe'    => $uniqueRules('name_ar'),
                'nom_francais' => $uniqueRules('name_fr'),
                'nom_anglais'  => $uniqueRules('name_en'),
                'specialite' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (isset($this->specialtiesCache[$value])) {
                            return true;
                        }
                        foreach (array_keys($this->specialtiesCache) as $specName) {
                            if (Str::contains($specName, $value)
                                // || levenshtein($specName, $value) <= 3
                            ) {
                                return true;
                            }
                        }
                        $fail(__('imports.services.specialty_not_found', ['value' => $value]));
                    }
                ],
                'chef_de_service' => ['required', Rule::in(array_keys($this->personnelCache))],
                'telephone'       => ['required', 'digits:9', new LandLineNumberExist(new Service())],
                'fax'             => ['nullable', 'digits:9', new LandLineNumberExist(new Service())],
                'email' => [
                    'nullable',
                    'email',
                    'max:255',
                    Rule::unique('services')
                        ->whereNull('deleted_at')
                ],
                'nombre_de_lits' => 'nullable|integer|min:0',
                'nombre_de_specialistes' => 'nullable|integer|min:0',
                'nombre_de_medecins' => 'nullable|integer|min:0',
                'nombre_de_paramedicaux' => 'nullable|integer|min:0',
            ],
            [],
            [
                'nom_francais' => __('imports.attributes.services.name_fr'),
                'nom_anglais' => __('imports.attributes.services.name_en'),
                'nom_arabe' => __('imports.attributes.services.name_ar'),
                'telephone'     => __('imports.attributes.services.tel'),
                'fax'     => __('imports.attributes.services.fax'),
                'chef_de_service' => __('imports.attributes.services.head_service'),
                'specialite'    => __('imports.attributes.services.specialty'),
                'email' => __('imports.attributes.services.email'),
                'nombre_de_lits' => __('imports.attributes.services.beds_number'),
                'nombre_de_specialistes' => __('imports.attributes.services.specialists_number'),
                'nombre_de_medecins' => __('imports.attributes.services.physicians_number'),
                'nombre_de_paramedicaux' => __('imports.attributes.services.paramedics_number'),
            ]

        );
    }

    protected function addValidationErrors(array $errors, int $lineNumber): void
    {
        foreach ($errors as $error) {
            $this->errors[] = __("imports.line_number_error", ['number' => $lineNumber]) . " : " . $error;
        }
    }



    protected function prepareDataForBulkInsert(array $row): void
    {
        // Normalize specialty search
        $search = Str::lower(trim($row["specialite"] ?? ''));

        $specialtyId = collect($this->specialtiesCache)
            ->first(function ($value, $key) use ($search) {
                return Str::contains(Str::lower($key), $search);
            });

        $this->services[] = [
            "name_fr"            => $row["nom_francais"],
            "name_ar"            => $row["nom_arabe"],
            "name_en"            => $row["nom_anglais"],
            "specialty_id"       => $specialtyId,
            "head_of_service_id" => $this->personnelCache[$row["chef_de_service"]] ?? null,
            "tel"                => $row["telephone"],
            "fax"                => $row["fax"] ?? null,
            'email'              => $row["email"],
            'beds_number'        => $row['nombre_de_lits'],
            'specialists_number'        => $row['nombre_de_specialistes'],
            'physicians_number'        => $row['nombre_de_medecins'],
            'paramedics_number'        => $row['nombre_de_paramedicaux'],
            "created_at"         => now(),
            "updated_at"         => now(),
        ];
    }

    protected function finalizeBulkInsert(): void
    {
        if (empty($this->services)) {
            return;
        }
        try {
            Service::insert($this->services);
        } catch (\Throwable $e) {
            Log::error("ServiceImport bulk insert error", [
                "message" => $e->getMessage(),
                "trace"   => $e->getTraceAsString()
            ]);
            $this->errors[] = __("imports.common.bulk_insert_error");
        } finally {
            $this->services = []; // always clear buffer
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
