<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name', 'slug',
    ];

    /**
     * Attributes hidden during serialization (e.g. API responses).
     */
    protected $hidden = ['pivot', "deleted_at"];

    // Enables support for soft deletes and manages the deleted_at column
    protected $dates = ['deleted_at'];

    /**
     * Define a many-to-many relationship with the User model.
     * Eloquent automatically assumes the pivot table is "role_user".
     */
    public function users()
    {
        // return $this->belongsToMany(Role::class, 'role_user'); // Not needed due to Laravel naming conventions
        return $this->belongsToMany(User::class)->withTimestamps();
    }

        public static function coordinator()
    {
        return Cache::rememberForever('role_coordinator', fn () =>
            static::where('slug', 'service_admin')->first()
        );
    }

        public static function appointmentsLocationAdmin()
    {
        return Cache::rememberForever('role_appointments_location_admin', fn () =>
            static::where('slug', 'appointments_location_admin')->first()
        );
    }
}
