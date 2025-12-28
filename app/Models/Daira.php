<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Daira extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dairates';

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
        'wilaya_id',
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
     * Relationship: Daira belongs to a Wilaya.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wilaya()
    {
        return $this->belongsTo(Wilaya::class);
    }

    public function establishments()
    {
        return $this->hasMany(Establishment::class);
    }

        public function getLocalizedDesignationAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"designation_{$locale}"} ?? $this->designation_fr ?? '';
    }

}
