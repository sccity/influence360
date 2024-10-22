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

        $october = Carbon::create(2024, 10, 1);
        $eventDates = [];

        // Generate 20 random dates in October, allowing duplicates for multiple events on same day
        for ($i = 0; $i < 20; $i++) {
            $eventDates[] = $october->copy()->addDays(rand(0, 30));
        }

        // Sort the dates
        usort($eventDates, function($a, $b) {
            return $a->timestamp - $b->timestamp;
        });

        foreach ($eventDates as $index => $meetingDate) {
            $startTime = Carbon::createFromTime(rand(8, 17), [0, 15, 30, 45][rand(0, 3)], 0);
            $endTime = (clone $startTime)->addMinutes(rand(30, 180));
            $guid = Str::uuid()->toString();

            LegislativeCalendar::create([
                'guid' => $guid,
                'description' => "Meeting of the " . $committees[array_rand($committees)],
                'meeting_id' => 'MTG' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'event_type' => $eventTypes[array_rand($eventTypes)],
                'link' => route('admin.legislative-calendar.event.view', ['id' => $guid]),
                'agenda_url' => 'https://example.com/agenda-' . ($index + 1),
                'minutes_url' => 'https://example.com/minutes-' . ($index + 1),
                'media_url' => 'https://example.com/media-' . ($index + 1),
                'emtg_url' => 'https://example.com/emtg-' . ($index + 1),
                'ics_url' => 'https://example.com/ics-' . ($index + 1),
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
