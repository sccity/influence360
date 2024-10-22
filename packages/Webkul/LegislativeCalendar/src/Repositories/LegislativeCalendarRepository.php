<?php

namespace Webkul\LegislativeCalendar\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\LegislativeCalendar\Contracts\LegislativeCalendar;

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
        return $this->model->where('mtg_time', '>=', now())
            ->orderBy('mtg_time', 'asc')
            ->take($limit)
            ->get();
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
