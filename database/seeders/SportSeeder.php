<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sports')->insert([
            'sport_title' => 'Cricket',
            'sport_fees' => '5000',
            'academy_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sports')->insert([
            'sport_title' => 'Football',
            'sport_fees' => '5000',
            'academy_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sports')->insert([
            'sport_title' => 'Boxing',
            'sport_fees' => '5000',
            'academy_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sports')->insert([
            'sport_title' => 'Cricket',
            'sport_fees' => '5000',
            'academy_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sports')->insert([
            'sport_title' => 'Football',
            'sport_fees' => '5000',
            'academy_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sports')->insert([
            'sport_title' => 'Boxing',
            'sport_fees' => '5000',
            'academy_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        DB::table('sports')->insert([
            'sport_title' => 'Cricket',
            'sport_fees' => '5000',
            'academy_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sports')->insert([
            'sport_title' => 'Football',
            'sport_fees' => '5000',
            'academy_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sports')->insert([
            'sport_title' => 'Boxing',
            'sport_fees' => '5000',
            'academy_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
