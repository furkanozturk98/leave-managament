<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {

        User::create([
            'manager_id' => 1,
            'hr_id'      => 1,
            'name'       => 'admin',
            'email'      => 'admin@admin.com',
            'password'   => Hash::make('password'),
            'started_at' => now(),
            'is_admin'   => 1,
        ]);

        User::create([
            'manager_id' => 1,
            'hr_id'      => 1,
            'name'       => 'User',
            'email'      => 'user@user.com',
            'password'   => Hash::make('password'),
            'started_at' => now(),
        ]);

        User::create([
            'manager_id' => 1,
            'hr_id'      => 1,
            'name'       => 'John Doe',
            'email'      => 'johndoe@user.com',
            'password'   => Hash::make('password'),
            'started_at' => now(),
        ]);


    }
}
