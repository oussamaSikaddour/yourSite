<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GlobalBankTransfer extends Model
{
    use  SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        "date",
        "number",//must be a number between 1 and 999
        "reference",
        "total_amount",
        "motive_fr",
        "motive_ar",
        "motive_en",
        "establishment_id",
        "user_id"
    ];

    protected $hidden = [
        "deleted_at"
    ];

    // Relationship: A transfer is associated with one user (the person who made it)
    public function theInitiator()
    {
        return $this->belongsTo(User::class);
    }


    public function bankTransfers(): HasMany
    {
        return $this->hasMany(BankTransfer::class);
    }
    // Ensure total_amount is always stored as a positive float
    public function setTotalAmountAttribute($value)
    {
        $this->attributes['total_amount'] = abs((float)$value);
    }


    public function getMotiveAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"motive_$locale"} ?? $this->motive_fr ?? '';
    }
}
