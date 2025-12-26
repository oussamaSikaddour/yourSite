<?php

namespace App\Traits\Core\Common;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PDF;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

trait TextAndPdfTrait
{
    /**
     * Generate a text file and return its path and filename.
     *
     * @param array|string $content Content of the file.
     * @param string|null $filename Optional filename (auto-generated if null).
     * @param string $disk Storage disk to use (default: 'local').
     * @return array ['filePath' => string, 'fileName' => string]
     * @throws \Exception If file cannot be created.
     */
    private function generateTextFile(array|string $content, ?string $filename = null, string $disk = 'local'): array
    {
        if (!is_string($content) && !is_array($content)) {
            throw new \InvalidArgumentException('Content must be a string or an array.');
        }

        $contentString = is_array($content) ? implode("\n", $content) : $content;
        $filename = $filename ?? 'file_' . Str::random(10) . '.txt';

        Storage::disk($disk)->put($filename, $contentString);

        if (!Storage::disk($disk)->exists($filename)) {
            throw new \Exception("File could not be created: {$filename}");
        }

        return [
            'filePath' => Storage::disk($disk)->path($filename),
            'fileName' => $filename,
        ];
    }

    /**
     * Generate a text file containing error messages.
     *
     * @param array|string $errors Error content.
     * @param string $disk Storage disk to use (default: 'local').
     * @return array ['filePath' => string, 'fileName' => string]
     */
    public function generateErrorsTextFile(array|string $errors, string $disk = 'local'): array
    {
        $filename = 'errors_' . now()->format('Ymd_His') . '.txt';
        return $this->generateTextFile($errors, $filename, $disk);
    }

    /**
     * Delete a generated file from the given disk.
     *
     * @param string $filename The file name.
     * @param string $disk Storage disk to use (default: 'local').
     * @return bool True if deleted, false otherwise.
     */
    public function deleteGeneratedFile(string $filename, string $disk = 'local'): bool
    {
        return Storage::disk($disk)->exists($filename)
            ? Storage::disk($disk)->delete($filename)
            : false;
    }

    /**
     * Generate and return a downloadable PDF file from a view.
     *
     * @param string $viewName Blade view to render.
     * @param array $data Data passed to the view.
     * @param string $filename Output PDF file name (default: document.pdf).
     * @return BinaryFileResponse
     */
    public function generateAndDownloadPdf(
        string $viewName,
        array $data = [],
        string $filename = 'document.pdf'
    ): BinaryFileResponse {
        $pdf = PDF::loadView($viewName, $data);

        $tempDir = storage_path('app/temp/');
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        $tempFilePath = $tempDir . $filename;
        $pdf->save($tempFilePath);

        return response()
            ->download($tempFilePath, $filename)
            ->deleteFileAfterSend(true);
    }
}
