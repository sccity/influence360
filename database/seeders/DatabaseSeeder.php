<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Installer\Database\Seeders\DatabaseSeeder as KrayinDatabaseSeeder;
use Webkul\BillFiles\Database\Seeders\BillFileSeeder;
use Webkul\Bills\Database\Seeders\BillSeeder;
use Webkul\Contact\Database\Seeders\PersonSeeder;
use Webkul\LegislativeCalendar\Database\Seeders\LegislativeCalendarSeeder;

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
        $this->call(ContactSeeder::class);         
        $this->call(PersonSeeder::class);          
        $this->call(BillFileSeeder::class);
        $this->call(BillSeeder::class);
        $this->call(LegislativeCalendarSeeder::class);
    }
}
