<?php

namespace App\Traits\Core\Common;

use App\Models\File;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait ModelFileTrait
{
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////         FILE MANAGEMENT
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Generate a unique, sanitized filename for an uploaded file.
     */
protected function fileRealName(int $fileable_id, UploadedFile $file): string
{
    $random = bin2hex(random_bytes(5)); // 10 random hex chars
    $fileName = uniqid() . '-' . $fileable_id . '-' . $random . '.' . $file->extension();
    return preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $fileName);
}


    /**
     * Store the file in the public disk under `files/` and return its relative path.
     */
    protected function storeFile(string $fileName, UploadedFile $file): string
    {
        return $file->storeAs('files', $fileName, 'public');
    }

    /**
     * Upload a single file and create its corresponding File record.
     */
    protected function uploadAndCreateFile(
        UploadedFile $file,
        int $fileable_id,
        string $fileable_type,
        string $useCase,
        ?string $displayName = null
    ): File {
        $realName = $this->fileRealName($fileable_id, $file);
        $path = $this->storeFile($realName, $file);
        $url = Storage::disk('public')->url($path);
        $size = Storage::disk('public')->size($path);

        return File::create([
            'display_name'  => $displayName,
            'real_name'     => $realName,
            'path'          => $path,
            'url'           => $url,
            'size'          => $size,
            'use_case'      => $useCase,
            'fileable_id'   => $fileable_id,
            'fileable_type' => $fileable_type,
        ]);
    }

    /**
     * Upload multiple files and return the created File models.
     */
    protected function uploadAndCreateFiles(
        array $files,
        int $fileable_id,
        string $fileable_type,
        string $useCase
    ): array {
        $created = [];
        foreach ($files as $file) {
            $created[] = $this->uploadAndCreateFile($file, $fileable_id, $fileable_type, $useCase);
        }
        return $created;
    }

    /**
     * Replace all files for a model (optionally deleting old ones first).
     */
    protected function uploadAndUpdateFiles(
        array $files,
        int $fileable_id,
        string $fileable_type,
        string $useCase,
        bool $deleteOld = false
    ): array {
        if ($deleteOld) {
            $this->deleteFilesByModel($fileable_id, $fileable_type);
        }

        return $this->uploadAndCreateFiles($files, $fileable_id, $fileable_type, $useCase);
    }

    /**
     * Replace a single file (optionally deleting the old one first).
     */
    protected function uploadAndUpdateFile(
        UploadedFile $file,
        int $fileable_id,
        string $fileable_type,
        string $useCase,
         ?string $displayName = null,
        bool $deleteOld = true

    ): File {
        if ($deleteOld) {
            $this->deleteFilesByModel($fileable_id, $fileable_type);
        }

        return $this->uploadAndCreateFile($file, $fileable_id, $fileable_type, $useCase,$displayName);
    }

    /**
     * Delete a single file via model logic.
     */
    public function deleteFile(File $file): void
    {
        $file->deleteFromDisk();
    }

    /**
     * Delete multiple files via model logic.
     */
    public function deleteFiles(Collection $files): void
    {
        File::deleteCollection($files);
    }

    /**
     * Delete all files associated with a given model (chunked for memory efficiency).
     */
    protected function deleteFilesByModel(int $fileable_id, string $fileable_type): void
    {
        File::where('fileable_id', $fileable_id)
            ->where('fileable_type', $fileable_type)
            ->chunk(100, function ($files) {
                File::deleteCollection($files);
            });
    }
}
