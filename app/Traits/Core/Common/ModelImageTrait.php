<?php

namespace App\Traits\Core\Common;

use App\Models\Image;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait ModelImageTrait
{
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////         IMAGE MANAGEMENT
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Generate a unique, sanitized filename for an uploaded image.
     */
protected function imageRealName(int $imageable_id, UploadedFile $image): string
{
    $random = bin2hex(random_bytes(5)); // 10 random hex chars
    $imageName = uniqid() . '-' . $imageable_id . '-' . $random . '.' . $image->extension();
    return preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $imageName);
}

    /**
     * Store the image in the public disk under `images/` and return its relative path.
     */
    protected function storeImageFile(string $imageName, UploadedFile $image): string
    {
        return $image->storeAs('images', $imageName, 'public');
    }

    /**
     * Upload a single image, store it, and create a corresponding Image record.
     */
    protected function uploadAndCreateImage(
        UploadedFile $image,
        int $imageable_id,
        string $imageable_type,
        string $useCase,
        ?string $displayName = null
    ): Image {
        $realName = $this->imageRealName($imageable_id, $image);
        $path = $this->storeImageFile($realName, $image);

        $dimensions = @getimagesize($image->getRealPath());
        $width = $dimensions[0] ?? null;
        $height = $dimensions[1] ?? null;

        $url = Storage::disk('public')->url($path);
        $size = Storage::disk('public')->size($path);

        return Image::create([
            'display_name'  => $displayName,
            'real_name'     => $realName,
            'path'          => $path,
            'url'           => $url,
            'size'          => $size,
            'width'         => $width,
            'height'        => $height,
            'use_case'      => $useCase,
            'imageable_id'  => $imageable_id,
            'imageable_type'=> $imageable_type,
        ]);
    }

    /**
     * Upload multiple images and return the created Image models.
     */
    protected function uploadAndCreateImages(
        array $images,
        int $imageable_id,
        string $imageable_type,
        string $useCase
    ): array {
        $created = [];
        foreach ($images as $image) {
            $created[] = $this->uploadAndCreateImage($image, $imageable_id, $imageable_type, $useCase);
        }
        return $created;
    }

    /**
     * Replace all images for a model (optionally deleting old ones first).
     */
    protected function uploadAndUpdateImages(
        array $images,
        int $imageable_id,
        string $imageable_type,
        string $useCase,
        bool $deleteOld = true
    ): array {
        if ($deleteOld) {
            $this->deleteImagesByModel($imageable_id, $imageable_type);
        }

        return $this->uploadAndCreateImages($images, $imageable_id, $imageable_type, $useCase);
    }

    /**
     * Replace a single image (optionally deleting the old one first).
     */
    protected function uploadAndUpdateImage(
        UploadedFile $image,
        int $imageable_id,
        string $imageable_type,
        string $useCase,
      ?string $displayName = null,
        bool $deleteOld = true
    ): Image {
        if ($deleteOld) {
            $this->deleteImagesByModel($imageable_id, $imageable_type,$useCase);
        }

        return $this->uploadAndCreateImage($image, $imageable_id, $imageable_type, $useCase ,
    $displayName);
    }

    /**
     * Delete a single image via model logic.
     */
    public function deleteImage(Image $image): void
    {
        $image->deleteFromDisk();
    }

    /**
     * Delete multiple images via model logic.
     */
    public function deleteImages(Collection $images): void
    {
        Image::deleteCollection($images);
    }

    /**
     * Delete all images associated with a given model (chunked for memory efficiency).
     */
protected function deleteImagesByModel(
    int $imageableId,
    string $imageableType,
    ?string $useCase = null
): void {
    Image::where('imageable_id', $imageableId)
        ->where('imageable_type', $imageableType)
        ->when($useCase, fn ($q) => $q->where('use_case', $useCase))
        ->chunkById(100, function ($images) {
            Image::deleteCollection($images);
        });
}

}
