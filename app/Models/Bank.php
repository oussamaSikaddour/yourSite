<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Bank extends Model
{
    // Enables model factory and soft deletes
    use HasFactory, SoftDeletes;

    // Indicates the column used to track soft deletion
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "code",               // Unique bank code
        "designation_ar",     // Arabic name
        "designation_fr",     // French name
        "designation_en",     // English name
        "acronym"             // Acronym of the bank
    ];

    /**
     * The attributes that should be hidden in arrays (e.g., API responses).
     *
     * @var array<int, string>
     */
    protected $hidden = [
        "deleted_at"          // Hides soft deletion timestamp
    ];

    /**
     * Accessor to return the localized designation based on the current app locale.
     */
    public function getLocalizedDesignationAttribute(): string
    {
        $locale = app()->getLocale(); // e.g., 'ar', 'fr', or 'en'
        return $this->{"designation_$locale"} ?? $this->designation_fr ?? '';
    }
}
