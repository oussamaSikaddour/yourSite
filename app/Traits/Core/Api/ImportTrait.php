<?php

namespace App\Traits\Core\Api;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;
use InvalidArgumentException;

trait ImportTrait
{
    /**
     * Handles the generic import of an Excel file.
     *
     * @param UploadedFile $excelFile       The uploaded Excel file instance.
     * @param string       $importClassName The import class name (e.g., 'UsersImport').
     * @param array        $parameters      Optional constructor parameters for the import class.
     * @return string
     *
     * @throws InvalidArgumentException
     * @throws Throwable
     */
    protected function handleExcelImport(
        UploadedFile $excelFile,
        string $importClassName,
        array $parameters = []
    ): string {
        $importClass = "App\\Imports\\{$importClassName}";

        if (!class_exists($importClass)) {
            throw new InvalidArgumentException("Import class '{$importClass}' not found.");
        }

        DB::beginTransaction();

        try {
            // Perform the import
            Excel::import(new $importClass(...$parameters), $excelFile->getRealPath());

            DB::commit();

            return "Import successful: {$importClassName}.";
        } catch (Throwable $e) {
            DB::rollBack();

            // Log the error for debugging
            report($e);

            // Optionally, wrap the exception with a more descriptive message
            throw $e;
        }
    }
}
