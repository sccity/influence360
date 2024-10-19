<?php

namespace Webkul\Admin\Http\Controllers;

use Webkul\Admin\Helpers\Dashboard;
use Webkul\Admin\Http\Controllers\Bills\BillController;
use Webkul\BillFiles\Repositories\BillFileRepository;

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
        protected BillController $billController,
        protected BillFileRepository $billFileRepository
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $trackedBills = $this->billController->getTrackedBills();
        $latestBillFiles = $this->billFileRepository->getModel()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin::dashboard.index', [
            'startDate' => $this->dashboardHelper->getStartDate(),
            'endDate'   => $this->dashboardHelper->getEndDate(),
            'trackedBills' => $trackedBills,
            'latestBillFiles' => $latestBillFiles,
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
