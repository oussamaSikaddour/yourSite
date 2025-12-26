<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bonus extends Model
{
    use  SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        "titled_fr",
        "titled_ar",
        "titled_en",
        "amount",
    ];

    protected $hidden = [
        "deleted_at"
    ];



    public function getTitledAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"titled_$locale"} ?? $this->titled_fr ?? '';
    }

}
