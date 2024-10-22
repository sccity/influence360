<?php

namespace Webkul\Admin\Http\Controllers\LegislativeCalendar;

use Webkul\Admin\Http\Controllers\Controller;
use Webkul\LegislativeCalendar\Repositories\LegislativeCalendarRepository;

class LegislativeCalendarController extends Controller
{
    protected $legislativeCalendarRepository;

    public function __construct(LegislativeCalendarRepository $legislativeCalendarRepository)
    {
        $this->legislativeCalendarRepository = $legislativeCalendarRepository;
    }

    public function index()
    {
        $start = now()->subMonths(6)->startOfMonth();
        $end = now()->addMonths(6)->endOfMonth();

        $events = $this->legislativeCalendarRepository->getEventsForCalendar($start, $end);

        return view('admin::legislative-calendar.index', compact('events'));
    }

    public function viewEvent($id)
    {
        $event = $this->legislativeCalendarRepository->getById($id);

        if (!$event) {
            abort(404);
        }

        return view('admin::legislative-calendar.event', compact('event'));
    }

    public function getEvents()
    {
        $start = request('start');
        $end = request('end');

        $events = $this->legislativeCalendarRepository->getEventsForCalendar($start, $end);

        return response()->json($events);
    }
}
