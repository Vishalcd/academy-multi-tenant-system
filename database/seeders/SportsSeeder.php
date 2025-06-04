<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sports')->insert([
            'id' => 1,
            'sport_title' => 'Cricket',
            'sport_fees' => 2000,
            'academy_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sports')->insert([
            'id' => 2,
            'sport_title' => 'FootBall',
            'sport_fees' => 4000,
            'academy_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sports')->insert([
            'id' => 3,
            'sport_title' => 'Badminton',
            'sport_fees' => 1500,
            'academy_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sports')->insert([
            'id' => 4,
            'sport_title' => 'Cricket',
            'sport_fees' => 2000,
            'academy_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sports')->insert([
            'id' => 5,
            'sport_title' => 'FootBall',
            'sport_fees' => 4000,
            'academy_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sports')->insert([
            'id' => 6,
            'sport_title' => 'Badminton',
            'sport_fees' => 1500,
            'academy_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sports')->insert([
            'id' => 7,
            'sport_title' => 'Cricket',
            'sport_fees' => 2000,
            'academy_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sports')->insert([
            'id' => 8,
            'sport_title' => 'FootBall',
            'sport_fees' => 4000,
            'academy_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sports')->insert([
            'id' => 9,
            'sport_title' => 'Badminton',
            'sport_fees' => 1500,
            'academy_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
