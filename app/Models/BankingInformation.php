<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankingInformation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that should be cast to date objects.
     *
     * @var array<int, string>
     */
    protected $table = 'banking_information'; // Explicitly sets the table name

    protected $casts = [
        'deleted_at' => 'datetime', // Enables proper handling of deleted_at timestamps
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'agency_ar',         // Arabic name of the agency
        'agency_en',         // English name of the agency
        'agency_fr',         // French name of the agency
        'agency_code',       // Code identifying the agency
        'account_number',    // Bank account number
        'bank_id',           // Foreign key to the Bank model
        "bankable_id",       // Polymorphic ID for related model (User, Establishment, etc.)
        "bankable_type",     // Polymorphic type for related model
        "is_active"          // Boolean to indicate if this info is active
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at', // Hides the deleted_at timestamp when converting to array/json
    ];

    /**
     * Polymorphic relation to the parent model (e.g., User or Establishment).
     */
    public function bankable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Standard relation to the Bank model.
     */
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function getAgencyAttribute(): string
    {
        $field = 'agency_' . app()->getLocale();
        return $this->{$field} ?? $this->agency_fr ?? '';
    }
}
