<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Field extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'designation_ar',
        'designation_fr',
        'designation_en',
        'acronym',
    ];

    /**
     * The attributes that should be hidden in arrays.
     */
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * Casts.
     */
    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationships.
     */
    public function occupations()
    {
        return $this->hasMany(Occupation::class);
    }

    /**
     * A field has many grades.
     */
    public function grades()
    {
        return $this->hasMany(FieldGrade::class);
    }

    /**
     * A field has many specialties.
     */
    public function specialties()
    {
        return $this->hasMany(FieldSpecialty::class);
    }

    /**
     * Accessor: Get localized designation based on current locale.
     */
    public function getLocalizedDesignationAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"designation_{$locale}"} ?? $this->designation_fr ?? '';
    }
}
