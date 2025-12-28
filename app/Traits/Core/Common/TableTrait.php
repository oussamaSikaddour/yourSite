<?php

namespace App\Traits\Core\Common;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait TableTrait
{
    use TextAndPdfTrait;

    public $excelFile;
    public $temporaryExcelUrl;
    public $sortBy = "created_at";
    public $sortDirection = "ASC";
    public $filters = [];
    public $perPage = "20";
    public $perPageOptions = ["20" => "20", "50" => "50", "100" => "100"];

    /**
     * Toggle sort direction or change sort column.
     */
    public function toggleSortBy(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'ASC' ? 'DESC' : 'ASC';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'DESC';
        }
    }

    /**
     * Add a filter option for UI or logic.
     */
    private function addFilter(string $name, string $label, $data, ?bool $toTranslate = null): void
    {
        $this->filters[] = compact('name', 'label', 'data', 'toTranslate');
    }

    /**
     * Dynamically update filter data.
     */
    private function updateFilterData(string $name, $newData): void
    {
        foreach ($this->filters as &$filter) {
            if ($filter['name'] === $name) {
                $filter['data'] = $newData;
                break;
            }
        }
    }

    /**
     * Apply Excel styles to header row.
     */
    private function applyHeaderStyles($worksheet, array $headers): void
    {
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DDDDDD']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];

        $columnIndex = 'A';
        foreach ($headers as $header) {
            $worksheet->setCellValue($columnIndex . '1', $header);
            $worksheet->getStyle($columnIndex . '1')->applyFromArray($headerStyle);
            $worksheet->getColumnDimension($columnIndex)->setAutoSize(true);
            $columnIndex++;
        }
    }

    /**
     * Fill worksheet with provided row data.
     */
    private function fillWorksheetWithData($worksheet, array $data): void
    {
        $rowIndex = 2;
        foreach ($data as $row) {
            $columnIndex = 'A';
            foreach ($row as $cellValue) {
                $worksheet->setCellValue($columnIndex . $rowIndex, $cellValue);
                $worksheet->getStyle($columnIndex . $rowIndex)
                          ->getNumberFormat()
                          ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
                $columnIndex++;
            }
            $rowIndex++;
        }
    }

    /**
     * Stream a generated spreadsheet file as response.
     */
    private function streamExcelFile(Spreadsheet $spreadsheet, string $fileName): StreamedResponse
    {
        $writer = new Xlsx($spreadsheet);

        return response()->stream(
            function () use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '.xlsx"',
            ]
        );
    }

    /**
     * Generate an Excel file and stream it (via a data provider callback).
     */
    public function generateExcel(callable $dataProvider, string $fileName): ?StreamedResponse
    {
        try {
            $tableData = $dataProvider();

            if (empty($tableData)) {
                throw new \Exception("No data provided for Excel generation.");
            }

            $spreadsheet = new Spreadsheet();
            $worksheet = $spreadsheet->getActiveSheet();

            $this->applyHeaderStyles($worksheet, array_keys($tableData[0]));
            $this->fillWorksheetWithData($worksheet, $tableData);

            return $this->streamExcelFile($spreadsheet, $fileName);
        } catch (\Exception $e) {
            Log::error("Excel generation failed: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate an Excel file with headers only (no data).
     */
    public function generateEmptyExcelWithHeaders(string $fileName, array $headers): StreamedResponse
    {
        return $this->generateExcel(function () use ($headers) {
            return [array_fill_keys($headers, "")];
        }, $fileName);
    }

    /**
     * Import Excel file using a given Laravel Excel import class.
     */
    public function uploadExcelFile(string $importClassName, string $uploadMessage, array $parameters = [])
    {
        try {
            $importClass = "App\\Imports\\$importClassName";

            if (!class_exists($importClass)) {
                throw new \InvalidArgumentException("Import class '$importClass' not found.");
            }

            if (!$this->excelFile) {
                throw new \RuntimeException("No file uploaded for processing.");
            }

            $path = $this->excelFile->getRealPath();
            DB::beginTransaction();

            Excel::import(new $importClass(...$parameters), $path);

            DB::commit();
            $this->dispatch('open-toast', $uploadMessage);
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->dispatch('open-errors', [$e->getMessage()]);
            return $this->generateErrorsTextFile($e->getMessage());
        }

        return null;
    }

    /**
     * Handle validation + triggering of Excel import logic.
     */
    public function whenExcelFileUploaded(string $importClassName, string $uploadMessage, array $parameters = [])
    {
        if ($this->excelFile) {
            $extension = $this->excelFile->getClientOriginalExtension();
            $allowedExtensions = ['xlsx', 'xls', 'csv'];

            if (!in_array($extension, $allowedExtensions)) {
                $this->reset(['excelFile', 'temporaryExcelUrl']);
                $this->dispatch('open-errors', [__("tables.common.excel.not-valid")]);
            } else {
                return $this->uploadExcelFile($importClassName, $uploadMessage, $parameters);
            }
        }
    }
}
