<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class GeneralSetting extends Model
{
    use HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Table Properties
    |--------------------------------------------------------------------------
    */

    /** @var string[] */
    protected $fillable = [
        'app_name',
        'acronym',
        'email',
        'phone',
        'landline',
        'fax',
        'address_fr',
        'address_ar',
        'address_en',
        'map',
        'maintenance',
        'inaugural_year',
        'youtube',
        'facebook',
        'linkedin',
        'github',
        'instagram',
        'tiktok',
        'twitter',            // X (formerly Twitter)
        'threads',      // Meta Threads
        'snapchat',     // Snapchat
        'pinterest',    // Pinterest
        'reddit',       // Reddit
        'telegram',     // Telegram channel or profile
        'whatsapp',     // WhatsApp contact or group link
        'discord',      // Discord server or user invite
        'twitch',       // Twitch profile
    ];

    /** @var array */
    protected $casts = [
        'maintenance'  => 'boolean',
        'social_links' => 'array',
        'deleted_at'   => 'datetime',
    ];

    /** @var string[] */
    protected $hidden = ['deleted_at'];

    /** @var string[] */
    protected $appends = [
        'logo_url',
        'address',
        'has_logo',
        'formatted_phone'
    ];



        public function bankingInformation(): MorphMany
    {
        return $this->morphMany(BankingInformation::class, 'bankable');
    }
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function logo(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    public function getLogoUrlAttribute(): string
    {
        return $this->logo?->url ?? asset('assets/app/images/Logo.png');
    }

    public function getHasLogoAttribute(): bool
    {
        return (bool) $this->logo;
    }

    public function getAddressAttribute(): string
    {
        $field = 'address_' . app()->getLocale();
        return $this->{$field} ?? $this->address_fr ?? '';
    }

    public function getAllAddressesAttribute(): array
    {
        return [
            'fr' => $this->address_fr,
            'ar' => $this->address_ar,
            'en' => $this->address_en,
        ];
    }

    public function getFormattedPhoneAttribute(): ?string
    {
        return $this->phone
            ? preg_replace('/(\d{2})(?=\d)/', '$1 ', $this->phone)
            : null;
    }

    /**
     * Combine all social links into one array (for API or frontend display)
     */

    /*
    |--------------------------------------------------------------------------
    | Static Helpers
    |--------------------------------------------------------------------------
    */

    public static function current(): self
    {
        $locale = app()->getLocale();
        $cacheKey = "general_settings_{$locale}";

        return Cache::remember($cacheKey, now()->addHours(6), function () {
            return static::query()->firstOrCreate();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Model Events
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::saved(fn(self $model) => $model->clearCache());
        static::deleted(fn(self $model) => $model->clearCache());

        static::deleting(function (self $model): void {
            $model->logo()->withoutEvents(fn() => $model->logo()?->delete());
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Cache Management
    |--------------------------------------------------------------------------
    */

    public function clearCache(): void
    {
        foreach (['fr', 'ar', 'en'] as $locale) {
            Cache::forget("general_settings_{$locale}");
        }
    }
}
