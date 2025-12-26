<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Hero extends Model
{
    use HasFactory;

    // Specify the table name explicitly (optional if following Laravel naming conventions)
    protected $table = "heros";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "title_ar",      // Arabic version of the hero title
        "title_fr",      // French version of the hero title
        "title_en",      // English version of the hero title
        "sub_title_ar",  // Arabic version of the sub-title
        "sub_title_fr",  // French version of the sub-title
        "sub_title_en",  // English version of the sub-title,
         "introduction_fr",
         "introduction_ar",
         "introduction_en",
         "primary_call_to_action_fr",
         "primary_call_to_action_ar",
         "primary_call_to_action_en",
         "secondary_call_to_action_fr",
         "secondary_call_to_action_fr",
         "secondary_call_to_action_ar",
         "secondary_call_to_action_en",
    ];

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }


public function getFirstImageUrlAttribute(): string
{
    $firstImage = $this->images()->first();

    return $firstImage?->url ?? asset('img/default/hero.jpg');
}


        public function getTitleAttribute(): string
    {
        $field = 'title_' . app()->getLocale();
        return $this->{$field} ?? $this->title_fr ?? '';
    }
        public function getSubTitleAttribute(): string
    {
        $field = 'sub_title_' . app()->getLocale();
        return $this->{$field} ?? $this->sub_title_fr ?? '';
    }
        public function getIntroductionAttribute(): string
    {
        $field = 'introduction_' . app()->getLocale();
        return $this->{$field} ?? $this->introduction_fr ?? '';
    }
        public function getPrimaryCallToActionAttribute(): string
    {
        $field = 'primary_call_to_action_' . app()->getLocale();
        return $this->{$field} ?? $this->primary_call_to_action_fr ?? '';
    }
        public function getSecondaryCallToActionAttribute(): string
    {
        $field = 'secondary_call_to_action_' . app()->getLocale();
        return $this->{$field} ?? $this->secondary_call_to_action_fr ?? '';
    }
}
