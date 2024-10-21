<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Contact\Models\Organization;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $organizations = [
            ['name' => 'US Senate'],
            ['name' => 'US House of Representatives'],
            ['name' => 'UT Senate'],
            ['name' => 'UT House of Representatives'],
        ];

        foreach ($organizations as $org) {
            Organization::firstOrCreate(['name' => $org['name']]);
        }
    }
}
