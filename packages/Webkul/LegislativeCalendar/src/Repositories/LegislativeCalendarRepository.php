<?php

namespace Webkul\LegislativeCalendar\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\LegislativeCalendar\Contracts\LegislativeCalendar;

class LegislativeCalendarRepository extends Repository
{
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
        return $this->model()
            ->where('mtg_time', '>=', now())
            ->orderBy('mtg_time', 'asc')
            ->take($limit)
            ->get();
    }
}
