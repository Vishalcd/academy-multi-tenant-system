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
            'name' => 'Maharaja Ganga Singh',
            'email' => 'academies@mail.com',
            'address' => 'academies 1 Office',
            'photo' => 'logos/xUOdbTmQJuINf0mjGr6wtApUg6uu9ln9NdcMwaNJ.png',
            'favicon' => 'logos/CwvHcSEasxGjakiqV0k11h9h1Td6p0NMF90XGHFi.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('academies')->insert([
            'name' => 'Kings Sport Venu',
            'email' => 'academies2@mail.com',
            'address' => 'academies 2 Office',
            'photo' => 'logos/aC7I1Jg2GN8UHnwr3GoHDl2010XebUIgTX6UVBPW.png',
            'favicon' => 'logos/61IXe1ZyzhgKFHkBjwaMZnEDcfw4ARSp5qCmhauY.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('academies')->insert([
            'name' => 'Good Sapherd Sports',
            'email' => 'academies3@mail.com',
            'address' => 'academies 3 Office',
            'photo' => 'logos/ai2e3Pe6xcrCt6kILqoOedT106Z6j11LkwKuO9Hg.png',
            'favicon' => 'logos/WjA1XV2qwg9SlDyqbfmM092sRtyGZeuBGTEl9mgm.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
