<?php

namespace Webkul\LegislativeCalendar\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\LegislativeCalendar\Contracts\LegislativeCalendar;
use Carbon\Carbon;

class LegislativeCalendarRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return LegislativeCalendar::class;
    }

    /**
     * Get upcoming events.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUpcomingEvents($limit = 5)
    {
        return $this->model->where('mtg_date', '>=', Carbon::today())
            ->orderBy('mtg_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->take($limit)
            ->get();
    }

    /**
     * Get events for FullCalendar.
     *
     * @param Carbon $start
     * @param Carbon $end
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEventsForCalendar(Carbon $start, Carbon $end)
    {
        return $this->model->whereBetween('mtg_date', [$start, $end])
            ->orderBy('mtg_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->description,
                    'start' => $event->mtg_date . 'T' . $event->start_time,
                    'end' => $event->mtg_date . 'T' . $event->end_time,
                    'url' => route('admin.legislative-calendar.event.view', ['id' => $event->id]),
                    'extendedProps' => [
                        'description' => $event->description,
                        'location' => $event->location,
                        'event_type' => $event->event_type,
                        'agenda_url' => $event->agenda_url,
                        'minutes_url' => $event->minutes_url,
                        'media_url' => $event->media_url,
                    ],
                ];
            });
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }
}
