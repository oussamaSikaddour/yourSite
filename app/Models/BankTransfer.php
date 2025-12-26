<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankTransfer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "amount",
        "user_id",
        "global_bank_transfer_id",
    ];

    protected $hidden = [
        "deleted_at",
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: A transfer is associated with one user (the initiator).
     */
    public function beneficiary()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: A transfer is associated with one global bank transfer.
     */
    public function globalBankTransfer()
    {
        return $this->belongsTo(GlobalBankTransfer::class);
    }

    /**
     * Mutator: Ensure amount is always stored as a positive float.
     */
    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = abs((float)$value);
    }


}
