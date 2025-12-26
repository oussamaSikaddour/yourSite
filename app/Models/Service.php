<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    // Enables support for soft deletes and manages the deleted_at column
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        "name_ar",
        "name_fr",
        "name_en",
        "introduction_fr",
        "introduction_ar",
        "introduction_en",
        "specialty_id",
        'head_of_service_id',
        'email',
        'tel',
        'fax',
        'beds_number',
        'specialists_number',
        'physicians_number',
        'paramedics_number',
    ];

    /**
     * The user responsible for the service.
     */
    public function headOfService(): BelongsTo
    {
        return $this->belongsTo(Person::class, "head_of_service_id");
    }
    public function specialty(): BelongsTo
    {
        return $this->belongsTo(FieldSpecialty::class, "specialty_id");
    }



    /**
     * Polymorphic relation to articles.
     */
    public function articles(): MorphMany
    {
        return $this->morphMany(Article::class, 'articleable');
    }

    /**
     * Polymorphic relation to sliders.
     */
    public function sliders(): MorphMany
    {
        return $this->morphMany(Slider::class, 'sliderable');
    }




    public function icon(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')
            ->where('use_case', 'icon');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */


    /**
     * Get the localized name based on the current app locale.
     */
    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"name_$locale"} ?? $this->name_fr ?? '';
    }
    public function getIntroductionAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"introduction_$locale"} ?? $this->introduction_fr ?? '';
    }
}
