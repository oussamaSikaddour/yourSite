<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slide extends Model
{
    use HasFactory, SoftDeletes;

    // Supports soft deletes using the deleted_at column
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        "title_fr",
        "title_ar",
        "title_en",
        "order",           // Defines the display order of the slide
        "content_fr",
        "content_ar",
        "content_en",
        "slider_id",       // Foreign key to the parent slider
    ];

    /**
     * Polymorphic one-to-one relation to an image.
     */
    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    /**
     * Each slide belongs to a slider.
     */
    public function slider(): BelongsTo
    {
        return $this->belongsTo(Slider::class);
    }

    /**
     * Get the localized title based on the current app locale.
     */
        public function getTitleAttribute(): string
    {
        $field = 'title_' . app()->getLocale();
        return $this->{$field} ?? $this->title_fr ?? '';
    }
        public function getContentAttribute(): string
    {
        $field = 'content_' . app()->getLocale();
        return $this->{$field} ?? $this->content_fr ?? '';
    }
}
