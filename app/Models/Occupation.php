<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Occupation extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'field_id',
        'field_specialty_id',
        'field_grade_id',
        'description_fr', // optional: text field like "Senior Consultant since 2018"
        'description_ar', // optional: text field like "Senior Consultant since 2018"
        'description_en', // optional: text field like "Senior Consultant since 2018"
        'experience',
        "is_active"
    ];

    public function holder()
    {
        return $this->belongsTo(Person::class,'person_id');
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    /**
     * An occupation belongs to a field specialty.
     */
    public function specialty()
    {
        return $this->belongsTo(FieldSpecialty::class,'field_specialty_id');
    }

    /**
     * An occupation belongs to a field grade.
     */
    public function grade()
    {
        return $this->belongsTo(FieldGrade::class ,'field_grade_id');
    }

        public function getLocalizedDesignationAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"designation_{$locale}"} ?? $this->designation_fr ?? '';
    }
}
