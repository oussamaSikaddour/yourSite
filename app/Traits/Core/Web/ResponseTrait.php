<?php

namespace App\Traits\Core\Web;

use App\Enum\Web\RoutesNames;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait ResponseTrait
{
    /**
     * Returns translated form attribute labels for a given model.
     *
     * @param string $model The model name (used in lang files).
     * @param array $attrs The list of attributes to translate.
     * @return array Associative array of translated attributes.
     */
    public function returnTranslatedResponseAttributes(string $model, array $attrs): array
    {
        return collect($attrs)
            ->mapWithKeys(fn($attr) => [$attr => __("forms.$model.$attr")])
            ->toArray();
    }

    /**
     * Standardized API response format.
     *
     * @param bool $status Whether the response is successful.
     * @param string|null $message Custom message (optional).
     * @param mixed|null $data Payload data (optional).
     * @param mixed|null $errors Errors (optional).
     * @return array JSON-style API response.
     */
    public function response(bool $status, ?string $message = null, $data = null, $errors = null): array
    {
        $message ??= $status
            ? "La réponse est revenue avec succès."
            : "Quelque chose s'est passé, il y a une erreur.";

        return [
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'errors' => $errors,
        ];
    }

    /**
     * Stream a file to the browser as a downloadable response.
     *
     * @param string $filePath Full path to the file.
     * @param string|null $filename Optional name to give the download.
     * @param string|null $contentType MIME type (auto-detected if null).
     * @return StreamedResponse Response object for file download.
     *
     * @throws \Exception If file does not exist.
     */
    public function streamFileDownload(string $filePath, ?string $filename = null, ?string $contentType = null): StreamedResponse
    {
        if (!File::exists($filePath)) {
            throw new \Exception("File not found: {$filePath}");
        }

        $filename = $filename ?? basename($filePath);
        $contentType = $contentType ?? File::mimeType($filePath);

        return response()->stream(
            fn() => readfile($filePath),
            200,
            [
                'Content-Type' => $contentType,
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]
        );
    }



}
