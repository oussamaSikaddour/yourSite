<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wilaya extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wilayates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'designation_ar',
        'designation_fr',
        'designation_en',
        'code',
    ];

    /**
     * The attributes that should be hidden when serializing.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    /**
     * Accessor: Get localized designation based on current app locale.
     *
     * @return string
     */
    public function getLocalizedDesignationAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"designation_{$locale}"} ?? $this->designation_fr ?? '';
    }

    /**
     * Relationship: Wilaya has many dairas.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dairates()
    {
        return $this->hasMany(Daira::class);
    }



}
