<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Cache;

class AboutUs extends Model
{
    use HasFactory;

    // Defines the database table name explicitly
    protected $table = "about_us";

    // Specifies the fillable fields for mass assignment
    protected $fillable = [
        "sub_title_fr",
        "sub_title_ar",
        "sub_title_en",
         'first_paragraph_fr',
         'first_paragraph_ar',
         'first_paragraph_en',
         'second_paragraph_en',
         'second_paragraph_fr',
         'second_paragraph_ar',
         'third_paragraph_fr',
         'third_paragraph_ar',
         'third_paragraph_en',
    ];

    /**
     * Defines a polymorphic one-to-one relationship with the Image model.
     * This allows the AboutUs model to be associated with a single image.
     */
    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }


    public function getSubTitleAttribute(): string
    {
        $field = 'sub_title_' . app()->getLocale();
        return $this->{$field} ?? $this->sub_title_fr ?? '';
    }

    public function getFirstParagraphAttribute(): string
    {
        $field = 'first_paragraph_' . app()->getLocale();
        return $this->{$field} ?? $this->first_paragraph_fr ?? '';
    }
    public function getSecondParagraphAttribute(): string
    {
        $field = 'second_paragraph_' . app()->getLocale();
        return $this->{$field} ?? $this->second_paragraph_fr ?? '';
    }
    public function getThirdParagraphAttribute(): string
    {
        $field = 'third_paragraph_' . app()->getLocale();
        return $this->{$field} ?? $this->third_paragraph_fr ?? '';
    }
    public function getImageUrlAttribute(): string
    {
        return $this->image?->url ?? asset('assets/app/images/aboutUs/ehs.jpg');
    }
}
