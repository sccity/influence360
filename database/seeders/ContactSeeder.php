<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('organizations')->insert([
            ['name' => 'US Senate'],
            ['name' => 'US House of Representatives'],
            ['name' => 'UT Senate'],
            ['name' => 'UT House of Representatives'],
        ]);
        DB::table('persons')->insert([
            [
                'name' => 'Generic Contact',
                'emails' => '[{"label": "work", "value": "no-reply@santaclarautah.gov"}]',
            ],
        ]);
    }
}
