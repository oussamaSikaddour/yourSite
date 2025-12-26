<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rating extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'doctor_id',
        'user_id',
        'rating',
        'comment_fr',
        'comment_ar',
        'comment_en',
    ];

    /**
     * The doctor being rated.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The user who rated.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
