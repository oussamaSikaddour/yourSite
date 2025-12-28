<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    // Ensure 'deleted_at' is treated as a Carbon date object
    protected $dates = ['deleted_at'];

    // Hide the deleted_at field when serializing the model
    protected $hidden = ["deleted_at"];

    // The attributes that can be mass assigned
    protected $fillable = [
        "message",  // The content of the message
        "email",    // The sender's email address
        "name",     // The sender's name
    ];
}
