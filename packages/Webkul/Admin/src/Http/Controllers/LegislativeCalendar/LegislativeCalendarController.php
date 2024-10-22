<?php

namespace Webkul\Admin\Http\Controllers\LegislativeCalendar;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\LegislativeCalendar\Repositories\LegislativeCalendarRepository;
use Webkul\Admin\DataGrids\LegislativeCalendar\LegislativeCalendarDataGrid;

class LegislativeCalendarController extends Controller
{
    public function __construct(protected LegislativeCalendarRepository $legislativeCalendarRepository)
    {
    }

    public function index(): View|JsonResponse
    {

        return view('admin::legislative-calendar.index');
    }
}