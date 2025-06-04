<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('academies')->insert([
            'id' => 1,
            'name' => 'Maharaja Ganga Singh',
            'email' => 'maharaja@mail.com',
            'address' => '2/3 Jwahar nagar SGNR',
            'photo' => 'academie_default.png',
            'favicon' => 'academie_default.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('academies')->insert([
            'id' => 2,
            'name' => 'Kings Sports VEnue',
            'email' => 'kings@mail.com',
            'address' => '2/3 Jwahar nagar SGNR',
            'photo' => 'academie_default.png',
            'favicon' => 'academie_default.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('academies')->insert([
            'id' => 3,
            'name' => 'Good Saphered Sports',
            'email' => 'goodsaphered@mail.com',
            'address' => '2/3 Jwahar nagar SGNR',
            'photo' => 'academie_default.png',
            'favicon' => 'academie_default.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
