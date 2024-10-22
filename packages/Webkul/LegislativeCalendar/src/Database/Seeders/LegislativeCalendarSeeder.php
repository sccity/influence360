<?php

namespace Webkul\LegislativeCalendar\Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\LegislativeCalendar\Models\LegislativeCalendar;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LegislativeCalendarSeeder extends Seeder
{
    public function run()
    {
        $committees = [
            'House Judiciary Committee',
            'Senate Finance Committee',
            'House Education Committee',
            'Senate Health Committee',
            'Joint Budget Committee',
        ];

        $eventTypes = [
            'Regular Session',
            'Public Hearing',
            'Executive Session',
            'Work Session',
            'Special Meeting',
        ];

        $locations = [
            'Room 101, State Capitol',
            'Room 205, Senate Building',
            'Room 303, House Office Building',
            'Main Auditorium, Legislative Office Building',
            'Conference Room A, State Office Complex',
        ];

        for ($i = 0; $i < 10; $i++) {
            $meetingDate = Carbon::now()->addDays(rand(1, 30));
            $startTime = Carbon::createFromTime(rand(8, 17), [0, 30][rand(0, 1)], 0);
            $endTime = (clone $startTime)->addHours(rand(1, 3));

            LegislativeCalendar::create([
                'guid' => Str::uuid()->toString(),
                'description' => "Meeting of the " . $committees[array_rand($committees)],
                'meeting_id' => 'MTG' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'event_type' => $eventTypes[array_rand($eventTypes)],
                'link' => 'https://example.com/meeting-' . ($i + 1),
                'agenda_url' => 'https://example.com/agenda-' . ($i + 1),
                'minutes_url' => 'https://example.com/minutes-' . ($i + 1),
                'media_url' => 'https://example.com/media-' . ($i + 1),
                'emtg_url' => 'https://example.com/emtg-' . ($i + 1),
                'ics_url' => 'https://example.com/ics-' . ($i + 1),
                'mtg_date' => $meetingDate->toDateString(),
                'start_time' => $startTime->toTimeString(),
                'end_time' => $endTime->toTimeString(),
                'location' => $locations[array_rand($locations)],
                'date_entered' => now(),
                'date_modified' => null,
            ]);
        }
    }
}
