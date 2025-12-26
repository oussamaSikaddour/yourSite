<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    // Enables soft deletes for the model
    use SoftDeletes;

    // Indicates the column used to track soft deletion
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "user_id",             // Foreign key to the author (User)
        "state",               // Status of the article
        "title_ar",            // Arabic title
        "title_fr",            // French title
        "title_en",            // English title
        "content_fr",          // French content
        "content_ar",          // Arabic content
        "content_en",          // English content
        'published_at',        // Publication date
        "articleable_type",    // Polymorphic type
        "articleable_id",      // Polymorphic ID
    ];

    /**
     * Polymorphic relationship to the owning model (e.g. establishment, category, etc.)
     */
    public function articleable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Belongs-to relationship with the User model (author).
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    /**
     * Polymorphic relationship: an article can have many images.
     */
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    /**
     * Accessor to get the title based on the current app locale.
     */
    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"title_$locale"} ?? $this->title_fr ?? '';
    }
    public function getContentAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"content_$locale"} ?? $this->content_fr ?? '';
    }
}
