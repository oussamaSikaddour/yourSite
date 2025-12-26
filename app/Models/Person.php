<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use HasFactory, SoftDeletes;

      protected $table = 'persons';
        protected $appends = ['full_name_fr', 'full_name_ar','full_name'];
    protected $fillable = [
        "last_name_ar",
        "first_name_ar",
        "last_name_fr",
        "first_name_fr",
        "card_number",
        "birth_place_ar",
        "birth_place_en",
        "birth_place_fr",
        "birth_date",
        "address_ar",
        "address_en",
        "address_fr",
        "phone",
        'is_paid',
        'employee_number',
        'social_number'
    ];

    protected $hidden = [
        "deleted_at"
    ];

    public function occupations(): HasMany
    {
        return $this->hasMany(Occupation::class);
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function bankingInformation(): MorphMany
    {
        return $this->morphMany(BankingInformation::class, 'bankable');
    }



    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    // ----------------------
    // MULTILINGUAL ACCESSORS
    // ----------------------



        public function getFullNameFrAttribute(): string
    {
        return trim("{$this->last_name_fr} {$this->first_name_fr}");
    }

    public function getFullNameArAttribute(): string
    {
        return trim("{$this->last_name_ar} {$this->first_name_ar}");
    }

    public function getFullNameAttribute():string{
        $locale = app()->getLocale();

        return $locale === 'ar'
            ? ($this->full_name_ar ?? $this->full_name_fr ?? 'unknown')
            : ($this->full_name_fr ?? 'unknown');

    }

    public function getLastNameAttribute(): string
    {
        $locale = app()->getLocale();

        return $locale === 'ar'
            ? ($this->last_name_ar ?? $this->last_name_fr ?? 'unknown')
            : ($this->last_name_fr ?? 'unknown');
    }

    public function getFirstNameAttribute(): string
    {
        $locale = app()->getLocale();

        return $locale === 'ar'
            ? ($this->first_name_ar ?? $this->first_name_fr ?? 'unknown')
            : ($this->first_name_fr ?? 'unknown');
    }

    public function getBirthPlaceAttribute(): string
    {
        $field = 'birth_place_' . app()->getLocale();

        return $this->{$field} ?? $this->birth_place_fr ?? '';
    }

    public function getAddressAttribute(): string
    {
        $field = 'address_' . app()->getLocale();

        return $this->{$field} ?? $this->address_fr ?? '';
    }
}
