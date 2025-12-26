<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FieldSpecialty extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'designation_ar',
        'designation_fr',
        'designation_en',
        'acronym',
        'field_id',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationships.
     */
    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    /**
     * A field specialty has many occupations.
     */
    public function occupations()
    {
        return $this->hasMany(Occupation::class);
    }


    /**
     * Accessor: Get localized designation.
     */
    public function getLocalizedDesignationAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"designation_{$locale}"} ?? $this->designation_fr ?? '';
    }
}
