<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Installer\Database\Seeders\DatabaseSeeder as KrayinDatabaseSeeder;
use Webkul\BillFiles\Database\Seeders\BillFileSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(KrayinDatabaseSeeder::class);

        $this->call(BillFileSeeder::class);
    }
}
