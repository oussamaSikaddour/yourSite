<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    // Ensure 'deleted_at' is handled as a Carbon date instance
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "title_ar",  // Arabic title of the menu
        "title_fr",  // French title of the menu
        "title_en",  // English title of the menu
        "state",     // Status of the menu (e.g., active/inactive)
    ];

    /**
     * Define a one-to-many relationship with the ExternalLink model.
     *
     * @return HasMany
     */
    public function externalLinks(): HasMany
    {
        return $this->hasMany(ExternalLink::class);
    }

    /**
     * Accessor to return the localized title based on the current locale.
     *
     * @return string
     */
    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();

        // Return the title in the current locale or fall back to French
        return $this->{"title_$locale"} ?? $this->title_fr ?? '';
    }
}
