<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'academy_id' => null,
            'name' => 'Vishal Bhati',
            'email' => 'admin@mail.com',
            'email_verified_at' => now(),
            'phone' => '6377582400',
            'address' => 'Admin Office',
            'photo' => 'default_user.jpg',
            'password' => Hash::make('password'), // Hashed password
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'academy_id' => 1,
            'name' => 'Abhishek',
            'email' => 'manager@mail.com',
            'email_verified_at' => now(),
            'phone' => '6377582401',
            'address' => 'Manager Office',
            'photo' => 'default_user.jpg',
            'password' => Hash::make('password'), // Hashed password
            'role' => 'manager',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'academy_id' => 2,
            'name' => 'Raman',
            'email' => 'manager2@mail.com',
            'email_verified_at' => now(),
            'phone' => '6377582402',
            'address' => 'Manager 2 Office',
            'photo' => 'default_user.jpg',
            'password' => Hash::make('password'), // Hashed password
            'role' => 'manager',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'academy_id' => 3,
            'name' => 'Rohit',
            'email' => 'manager3@mail.com',
            'email_verified_at' => now(),
            'phone' => '6377582403',
            'address' => 'Manager 2 Office',
            'photo' => 'default_user.jpg',
            'password' => Hash::make('password'), // Hashed password
            'role' => 'manager',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
