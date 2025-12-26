<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class Trend extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title_ar',
        'title_fr',
        'title_en',
        'state',
        'content_ar',
        'content_fr',
        'content_en',
        'user_id',
        'start_at',
        'end_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_at'   => 'datetime',
        'end_at'     => 'datetime',
        'deleted_at' => 'datetime',
    ];



    /**
     * Get the user that owns the trend.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Check if the trend is currently active (between start_at and end_at).
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->start_at instanceof Carbon && $this->end_at instanceof Carbon
            ? now()->between($this->start_at, $this->end_at)
            : false;
    }

    /**
     * Get the localized title based on app locale with fallback.
     */
    public function getLocalizedTitleAttribute(): ?string
    {
        return $this->getLocalizedField('title');
    }

    /**
     * Get the localized content based on app locale with fallback.
     */
    public function getLocalizedContentAttribute(): ?string
    {
        return $this->getLocalizedField('content');
    }

    /**
     * Get the localized field value (title/content) based on app locale.
     */
    protected function getLocalizedField(string $field): ?string
    {
        $locale = app()->getLocale();
        return $this->{$field . '_' . $locale} ?? $this->{$field . '_fr'} ?? null;
    }

    /**
     * Scope to get only active trends.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('start_at', '<=', now())
                     ->where('end_at', '>=', now());
    }
}
