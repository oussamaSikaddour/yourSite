<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExternalLink extends Model
{
    use HasFactory, SoftDeletes;

    // Allows soft deleting records (deleted_at will be handled automatically)
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "name_ar",     // Arabic name of the external link
        "name_fr",     // French name of the external link
        "name_en",     // English name of the external link
        "url",         // The actual URL of the external resource
        "menu_id",     // Foreign key referencing the related menu
    ];

    /**
     * Defines the relationship between ExternalLink and Menu.
     * Each external link belongs to a menu.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Accessor to get the localized name based on the current locale.
     * Falls back to French if translation is missing.
     */
    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"name_$locale"} ?? $this->name_fr ?? '';
    }
}
