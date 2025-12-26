<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class OurQuality extends Model
{
    use HasFactory;

    // Mass assignable fields
    protected $fillable = ['name_fr', 'name_ar', 'name_en', 'is_active'];

    /**
     * Get the associated image (polymorphic one-to-one).
     */
    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    /**
     * Accessor to get the localized name based on the app's current locale.
     */
    public function getLocalizedNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"name_$locale"} ?? $this->name_fr ?? '';
    }
}
