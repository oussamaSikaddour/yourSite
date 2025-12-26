<?php

namespace App\Models;

use App\Enum\NotificationFor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes, Prunable;

    /* -------------------------------------------------
     *  CONFIGURATION
     * ------------------------------------------------- */

    protected $fillable = [
        'message',
        'active',
        'for_type',
        'targetable_id',
        'targetable_type',
    ];

    protected $casts = [
        'active'       => 'boolean',
        'for_type'     =>NotificationFor::class,
        'deleted_at'   => 'datetime',
    ];

    protected $appends = ['short_message'];

    /* -------------------------------------------------
     *  RELATIONSHIPS
     * ------------------------------------------------- */

    public function targetable()
    {
        return $this->morphTo();
    }

    /* -------------------------------------------------
     *  SCOPES
     * ------------------------------------------------- */

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeRecent($query, int $limit = 10)
    {
        return $query->active()->latest()->take($limit);
    }

    /* -------------------------------------------------
     *  ACCESSORS
     * ------------------------------------------------- */

    public function getShortMessageAttribute(): string
    {
        return str($this->message)->limit(50);
    }

    /* -------------------------------------------------
     *  PRUNING (automatically cleaned up nightly)
     * ------------------------------------------------- */

    public function prunable()
    {
        return static::onlyTrashed()
                     ->where('deleted_at', '<=', now()->subDays(30));
    }
}
