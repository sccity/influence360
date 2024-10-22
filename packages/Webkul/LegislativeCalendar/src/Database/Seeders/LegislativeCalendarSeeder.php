<?php

namespace Webkul\LegislativeCalendar\Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\LegislativeCalendar\Models\LegislativeCalendar;
use Illuminate\Support\Str;

class LegislativeCalendarSeeder extends Seeder
{
    public function run()
    {
        $events = [
            [
                'guid' => Str::uuid()->toString(),
                'committee' => 'House Judiciary Committee',
                'committee_id' => 'HJC001',
                'mtg_time' => now()->addDays(5)->setTime(10, 0),
                'mtg_place' => 'Room 101, State Capitol',
                'link' => 'https://example.com/house-judiciary-meeting',
                'date_entered' => now(),
                'date_modified' => now(),
            ],
            [
                'guid' => Str::uuid()->toString(),
                'committee' => 'Senate Finance Committee',
                'committee_id' => 'SFC002',
                'mtg_time' => now()->addDays(7)->setTime(14, 30),
                'mtg_place' => 'Room 205, Senate Building',
                'link' => 'https://example.com/senate-finance-meeting',
                'date_entered' => now(),
                'date_modified' => now(),
            ],
            // Add more sample data as needed
        ];

        foreach ($events as $event) {
            LegislativeCalendar::create($event);
        }
    }
}
