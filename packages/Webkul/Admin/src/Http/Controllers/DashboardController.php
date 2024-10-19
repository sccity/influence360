<?php

namespace Webkul\Admin\Http\Controllers;

use Webkul\Admin\Helpers\Dashboard;
use Webkul\Admin\Http\Controllers\Bills\BillController;
use Webkul\BillFiles\Repositories\BillFileRepository;
use Webkul\Initiative\Repositories\InitiativeRepository;
use Webkul\Initiative\Repositories\StageRepository;
use Webkul\Contact\Repositories\OrganizationRepository;
use Webkul\Contact\Repositories\PersonRepository;
use Webkul\Admin\DataGrids\Activity\ActivityDataGrid;
use Carbon\Carbon;

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
        protected BillFileRepository $billFileRepository,
        protected InitiativeRepository $initiativeRepository,
        protected StageRepository $stageRepository,
        protected OrganizationRepository $organizationRepository,
        protected PersonRepository $personRepository
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subDays(30);

        $totalInitiatives = $this->initiativeRepository->count();
        $openInitiatives = $this->initiativeRepository->getOpenInitiativesCount();
        $topStage = $this->stageRepository->getTopStage();
        $avgTimeToClose = $this->initiativeRepository->getAverageTimeToClose();

        $recentInitiatives = $this->initiativeRepository->getRecentInitiatives(5);
        $trackedBills = $this->billController->getTrackedBills();
        $latestBillFiles = $this->billFileRepository->getLatestBillFiles(5);

        $initiativeGrowth = $this->calculateGrowthPercentage('initiatives');
        $timeToCloseImprovement = $this->calculateTimeToCloseImprovement();

        // Calculate open initiatives percentage
        $openInitiativesPercentage = $totalInitiatives > 0
            ? round(($openInitiatives / $totalInitiatives) * 100, 2)
            : 0;

        // Calculate top stage percentage
        $topStagePercentage = $totalInitiatives > 0
            ? round(($topStage->initiatives_count / $totalInitiatives) * 100, 2)
            : 0;

        return view('admin::dashboard.index', compact(
            'startDate',
            'endDate',
            'totalInitiatives',
            'openInitiatives',
            'openInitiativesPercentage',
            'topStage',
            'topStagePercentage',
            'avgTimeToClose',
            'recentInitiatives',
            'trackedBills',
            'latestBillFiles',
            'initiativeGrowth',
            'timeToCloseImprovement'
        ));
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

    private function calculateGrowthPercentage($metric)
    {
        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();

        $currentCount = $this->initiativeRepository->whereBetween('created_at', [$lastMonth, $now])->count();
        $previousCount = $this->initiativeRepository->whereBetween('created_at', [$lastMonth->copy()->subMonth(), $lastMonth])->count();

        if ($previousCount == 0) {
            return $currentCount > 0 ? 100 : 0;
        }

        return round((($currentCount - $previousCount) / $previousCount) * 100, 2);
    }

    private function calculateTimeToCloseImprovement()
    {
        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();

        $currentAvg = $this->initiativeRepository->getAverageTimeToClose($lastMonth, $now);
        $previousAvg = $this->initiativeRepository->getAverageTimeToClose($lastMonth->copy()->subMonth(), $lastMonth);

        if ($previousAvg == 0) {
            return $currentAvg < $previousAvg ? 100 : 0;
        }

        return round((($previousAvg - $currentAvg) / $previousAvg) * 100, 2);
    }

    public function activities()
    {
        return app(ActivityDataGrid::class)->toJson();
    }
}
