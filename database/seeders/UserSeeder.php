<?php

namespace Database\Seeders;


use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();
        $user = User::create([
            'email' => 'superAdmin@gmail.com',
            'password' => Hash::make('Vide=1342'),
            'name' => 'TheSuperAdmin',
        ]);
        $user->roles()->attach(Role::where('slug', 'super_admin')->first());
        $user->roles()->attach(Role::where('slug', 'admin')->first());

    }
}
