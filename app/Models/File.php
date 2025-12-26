<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $hidden = ['deleted_at'];

    protected $fillable = [
        'display_name',   // Optional name shown to the user
        'real_name',      // Actual file name stored on disk
        'path',           // Relative storage path
        'url',            // Public URL
        'size',           // Size in bytes
        'is_active',         // Optional custom status
        'use_case',       // Context of file usage (e.g., contract, CV, etc.)
        'fileable_id',    // Polymorphic ID
        'fileable_type',  // Polymorphic type
    ];

    /**
     * Polymorphic relationship.
     */
    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Delete this file from disk and database.
     */
    public function deleteFromDisk(): void
    {
        if ($this->path && Storage::disk('public')->exists($this->path)) {
            Storage::disk('public')->delete($this->path);
        }

        $this->delete();
    }

    /**
     * Delete a collection of files from disk and database.
     */
    public static function deleteCollection(Collection $files): void
    {
        foreach ($files as $file) {
            $file->deleteFromDisk();
        }
    }
}
