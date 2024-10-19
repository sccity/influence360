<?php

namespace Webkul\Admin\Http\Controllers;

use Webkul\Admin\Helpers\Dashboard;
use Webkul\Admin\Http\Controllers\Bills\BillController;

class DashboardController extends Controller
{
    /**
     * Request param functions
     *
     * @var array
     */
    protected $typeFunctions = [
        'over-all'             => 'getOverAllStats',
        'revenue-stats'        => 'getRevenueStats',
        'total-initiatives'    => 'getTotalInitiativesStats',
        'revenue-by-sources'   => 'getInitiativesStatsBySources',
        'revenue-by-types'     => 'getInitiativesStatsByTypes',
        'top-selling-products' => 'getTopSellingProducts',
        'top-persons'          => 'getTopPersons',
        'open-initiatives-by-states' => 'getOpenInitiativesByStates',
        'tracked-bills'        => 'getTrackedBills',
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected Dashboard $dashboardHelper,
        protected BillController $billController
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $trackedBills = $this->billController->getTrackedBills();

        return view('admin::dashboard.index', [
            'startDate' => $this->dashboardHelper->getStartDate(),
            'endDate'   => $this->dashboardHelper->getEndDate(),
            'trackedBills' => $trackedBills,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats()
    {
        $type = request()->query('type');

        if ($type === 'tracked-bills') {
            $stats = $this->billController->getTrackedBills();
        } else {
            $stats = $this->dashboardHelper->{$this->typeFunctions[$type]}();
        }

        return response()->json([
            'statistics' => $stats,
            'date_range' => $this->dashboardHelper->getDateRange(),
        ]);
    }
}
