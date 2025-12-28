<?php

namespace App\Traits\Core\Api;

use App\Traits\Core\Common\TextAndPdfTrait;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
 use Illuminate\Support\Facades\File;
use PDF;



trait ResponseTrait
{
    use TextAndPdfTrait;

    /**
     * Success response
     */
    public function responseSuccess(
        string $type,
        string|int|null $id,
        array $attributes = [],
        array $meta = [],
        array $links = [],
        int $status = 200
    ): JsonResponse {
        return response()->json([
            'data' => [
                'type' => $type,
                'id' => (string) $id,
                'attributes' => $attributes,
            ],
            'meta' => $this->appendTimestamp($meta),
            'links' => $links,
        ], $status);
    }

    /**
     * Collection response (supports arrays, collections, resources, and paginator)
     *
     * @param string $type
     * @param array|Collection|AnonymousResourceCollection|LengthAwarePaginator $items
     * @param array $meta
     * @param array $links
     * @param int $status
     */
    public function responseCollection(
        string $type,
        array|Collection|AnonymousResourceCollection|LengthAwarePaginator $items,
        array $meta = [],
        array $links = [],
        int $status = 200
    ): JsonResponse {
        // Normalize items to collection
        if ($items instanceof LengthAwarePaginator) {
            $collection = $items->getCollection();
            $meta = array_merge([
                'current_page' => $items->currentPage(),
                'from' => $items->firstItem(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'to' => $items->lastItem(),
                'total' => $items->total(),
            ], $meta);

            $links = $links ?: [
                'first' => $items->url(1),
                'last' => $items->url($items->lastPage()),
                'prev' => $items->previousPageUrl(),
                'next' => $items->nextPageUrl(),
            ];
        } elseif ($items instanceof AnonymousResourceCollection) {
            $collection = collect($items->resolve());
        } elseif (is_array($items)) {
            $collection = collect($items);
        } elseif ($items instanceof Collection) {
            $collection = $items;
        } else {
            $collection = collect();
        }

        $data = $collection->map(fn($item) => [
            'type' => $type,
            'id' => (string) ($item['id'] ?? $item['uuid'] ?? ''),
            'attributes' => collect(is_array($item) ? $item : (array) $item)
                ->except(['id', 'uuid']),
        ]);

        return response()->json([
            'data' => $data,
            'meta' => $this->appendTimestamp($meta),
            'links' => $links,
        ], $status);
    }

    /**
     * Error response with optional downloadable file
     */
    public function responseError(
        string $field = 'server',
        array|string $errors = 'An unexpected error occurred.',
        string $title = 'Server Error',
        int $status = 400,
        bool $withFile = false,
        array $headers = [],
        bool $privateFile = false
    ): JsonResponse {
        $errorsArray = is_array($errors)
            ? $errors
            : (str_contains($errors, "\n") ? explode("\n", $errors) : [$errors]);

        $jsonApiErrors = collect($errorsArray)->map(fn($message) => [
            'title' => $title,
            'detail' => $message,
            'source' => ['pointer' => "/data/attributes/{$field}"],
        ])->values();

        if ($withFile && !empty($errorsArray)) {
            try {
                $fileName = 'errors_' . now()->format('Ymd_His') . '.txt';
                $disk = $privateFile ? 'local' : 'public';
                $filePath = ($privateFile ? 'private/' : 'errors/') . $fileName;

                Storage::disk($disk)->put($filePath, implode("\n", $errorsArray));

                $downloadUrl = $privateFile
                    ? route('errors.download', ['fileName' => $fileName])
                    : Storage::disk('public')->url("errors/{$fileName}");

                $jsonApiErrors->push([
                    'title' => 'Error File Generated',
                    'detail' => 'A downloadable error file has been created.',
                    'meta' => [
                        'file' => [
                            'name' => $fileName,
                            'path' => $filePath,
                            'url' => $downloadUrl,
                        ],
                    ],
                ]);
            } catch (\Throwable $e) {
                $jsonApiErrors->push([
                    'title' => 'File Generation Failed',
                    'detail' => 'Could not generate error file: ' . $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'errors' => $jsonApiErrors,
            'meta' => $this->appendTimestamp(),
        ], $status, $headers);
    }





public function generatePdfAndReturnUrl(
    string $viewName,
    array $data = [],
    string $filename = 'document.pdf',
    string $disk = 'public' // 'public' or 'local'
): JsonResponse {
    $directory = 'pdfs/'; // folder inside storage disk
    $filePath = $directory . $filename;

    // Delete file if it already exists
    if (Storage::disk($disk)->exists($filePath)) {
        Storage::disk($disk)->delete($filePath);
    }

    // Generate PDF
    $pdf = PDF::loadView($viewName, $data);

    // Save PDF to storage
    Storage::disk($disk)->put($filePath, $pdf->output());

    // Generate URL
    $url = $disk === 'public'
        ? Storage::disk('public')->url($filePath)
        : route('pdf.download', ['file' => $filename]); // for private files, you can create a download route

    // Return JSON:API style response (like ResponseTrait)
    return $this->responseSuccess(
        type: 'pdf',
        id: $filename,
        attributes: [
            'path' => $filePath,
            'url' => $url,
        ]
    );
}

    /**
     * Simple test response
     */
    public function responseTest(array|string $test): JsonResponse
    {
        return response()->json([
            'test' => $test,
            'meta' => $this->appendTimestamp(),
        ], 200);
    }

    /**
     * Append timestamp to meta
     */
    protected function appendTimestamp(array $meta = []): array
    {
        return array_merge($meta, [
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
