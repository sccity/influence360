<?php

namespace Webkul\Admin\Http\Controllers\LegislativeCalendar;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\LegislativeCalendar\Repositories\LegislativeCalendarRepository;

class LegislativeCalendarController extends Controller
{ 
    public function __construct(protected LegislativeCalendarRepository $legislativeCalendarRepository)
    {
    }

    public function index(): View
    {
        $events = $this->legislativeCalendarRepository->all()->map(function ($event) {
            return [
                'title' => $event->committee,
                'start' => $event->mtg_time,
                'url' => $event->link,
                'extendedProps' => [
                    'description' => $event->mtg_place
                ]
            ];
        });

        return view('admin::legislative-calendar.index', compact('events'));
    }
}
