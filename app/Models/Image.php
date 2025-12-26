<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $hidden = ['deleted_at'];

    protected $fillable = [
        'display_name',    // Optional human-readable name shown to users
        'real_name',       // Actual filename stored on disk (always generated)
        'path',            // Storage path (e.g. "images/abc123.jpg")
        'url',             // Public URL to access the image
        'size',            // File size in bytes
        'width',           // Image width in pixels
        'height',          // Image height in pixels
        'is_active',       // Whether the image is active
        'use_case',        // Context/purpose (avatar, banner, etc.)
        'imageable_id',    // Polymorphic ID
        'imageable_type',  // Polymorphic type
    ];

    /**
     * Polymorphic relation to the parent model.
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Delete this image from disk and database.
     */
    public function deleteFromDisk(): void
    {
        if ($this->path && Storage::disk('public')->exists($this->path)) {
            Storage::disk('public')->delete($this->path);
        }

        $this->delete();
    }

    /**
     * Delete a collection of images from disk and database.
     */
    public static function deleteCollection(Collection $images): void
    {
        foreach ($images as $image) {
            $image->deleteFromDisk();
        }
    }
}
