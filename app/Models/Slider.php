<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Slider extends Model
{
    use HasFactory, SoftDeletes;



    /**
     * Enables soft deletes with deleted_at column.
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'state',
        'user_id',
        'position',
        'sliderable_type',
        'sliderable_id',
    ];

    /**
     * Default attribute values.
     */
    protected $attributes = [
        'position' => 1
    ];

    /**
     * The user who created this slider.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A slider can have many slides.
     */
    public function slides(): HasMany
    {
        return $this->hasMany(Slide::class);
    }


}
