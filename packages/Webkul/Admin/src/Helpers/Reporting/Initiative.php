<?php

namespace Webkul\Admin\Helpers\Reporting;

use Illuminate\Support\Facades\DB;
use Webkul\Initiative\Repositories\InitiativeRepository;
use Webkul\Initiative\Repositories\StageRepository;

class Initiative extends AbstractReporting
{
    /**
     * The channel ids.
     */
    protected array $stageIds;

    /**
     * The channel ids.
     */
    protected array $wonStageIds;

    /**
     * The channel ids.
     */
    protected array $lostStageIds;

    /**
     * Create a helper instance.
     *
     * @return void
     */
    public function __construct(
        protected InitiativeRepository $initiativeRepository,
        protected StageRepository $stageRepository
    ) {
        $this->wonStageIds = $this->stageRepository->where('code', 'won')->pluck('id')->toArray();

        $this->lostStageIds = $this->stageRepository->where('code', 'lost')->pluck('id')->toArray();

        parent::__construct();
    }

    /**
     * Returns current customers over time
     *
     * @param  string  $period
     */
    public function getTotalInitiativesOverTime($period = 'auto'): array
    {
        $this->stageIds = [];

        return $this->getOverTimeStats($this->startDate, $this->endDate, 'initiatives.id', 'created_at', $period);
    }

    /**
     * Returns current customers over time
     *
     * @param  string  $period
     */
    public function getTotalWonInitiativesOverTime($period = 'auto'): array
    {
        $this->stageIds = $this->wonStageIds;

        return $this->getOverTimeStats($this->startDate, $this->endDate, 'initiatives.id', 'closed_at', $period);
    }

    /**
     * Returns current customers over time
     *
     * @param  string  $period
     */
    public function getTotalLostInitiativesOverTime($period = 'auto'): array
    {
        $this->stageIds = $this->lostStageIds;

        return $this->getOverTimeStats($this->startDate, $this->endDate, 'initiatives.id', 'closed_at', $period);
    }

    /**
     * Retrieves total initiatives and their progress.
     */
    public function getTotalInitiativesProgress(): array
    {
        return [
            'previous' => $previous = $this->getTotalInitiatives($this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->getTotalInitiatives($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total initiatives by date
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getTotalInitiatives($startDate, $endDate): int
    {
        return $this->initiativeRepository
            ->resetModel()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    /**
     * Retrieves average initiatives per day and their progress.
     */
    public function getAverageInitiativesPerDayProgress(): array
    {
        return [
            'previous' => $previous = $this->getAverageInitiativesPerDay($this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->getAverageInitiativesPerDay($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves average initiatives per day
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getAverageInitiativesPerDay($startDate, $endDate): float
    {
        $days = $startDate->diffInDays($endDate);

        if ($days == 0) {
            return 0;
        }

        return $this->getTotalInitiatives($startDate, $endDate) / $days;
    }

    /**
     * Retrieves total initiative value and their progress.
     */
    public function getTotalInitiativeValueProgress(): array
    {
        return [
            'previous'        => $previous = $this->getTotalInitiativeValue($this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getTotalInitiativeValue($this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total initiative value
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getTotalInitiativeValue($startDate, $endDate): float
    {
        return $this->initiativeRepository
            ->resetModel()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('initiative_value');
    }

    /**
     * Retrieves average initiative value and their progress.
     */
    public function getAverageInitiativeValueProgress(): array
    {
        return [
            'previous'        => $previous = $this->getAverageInitiativeValue($this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getAverageInitiativeValue($this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves average initiative value
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getAverageInitiativeValue($startDate, $endDate): float
    {
        return $this->initiativeRepository
            ->resetModel()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->avg('initiative_value') ?? 0;
    }

    /**
     * Retrieves total won initiative value and their progress.
     */
    public function getTotalWonInitiativeValueProgress(): array
    {
        return [
            'previous'        => $previous = $this->getTotalWonInitiativeValue($this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getTotalWonInitiativeValue($this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves average won initiative value
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getTotalWonInitiativeValue($startDate, $endDate): ?float
    {
        return $this->initiativeRepository
            ->resetModel()
            ->whereIn('initiative_pipeline_stage_id', $this->wonStageIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->avg('initiative_value');
    }

    /**
     * Retrieves average lost initiative value and their progress.
     */
    public function getTotalLostInitiativeValueProgress(): array
    {
        return [
            'previous'        => $previous = $this->getTotalLostInitiativeValue($this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getTotalLostInitiativeValue($this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves average lost initiative value
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getTotalLostInitiativeValue($startDate, $endDate): ?float
    {
        return $this->initiativeRepository
            ->resetModel()
            ->whereIn('initiative_pipeline_stage_id', $this->lostStageIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->avg('initiative_value');
    }

    /**
     * Retrieves total initiative value by sources.
     */
    public function getTotalWonInitiativeValueBySources()
    {
        return $this->initiativeRepository
            ->resetModel()
            ->select(
                'initiative_sources.name',
                DB::raw('SUM(initiative_value) as total')
            )
            ->leftJoin('initiative_sources', 'initiatives.initiative_source_id', '=', 'initiative_sources.id')
            ->whereIn('initiative_pipeline_stage_id', $this->wonStageIds)
            ->whereBetween('initiatives.created_at', [$this->startDate, $this->endDate])
            ->groupBy('initiative_source_id')
            ->get();
    }

    /**
     * Retrieves total initiative value by types.
     */
    public function getTotalWonInitiativeValueByTypes()
    {
        return $this->initiativeRepository
            ->resetModel()
            ->select(
                'initiative_types.name',
                DB::raw('SUM(initiative_value) as total')
            )
            ->leftJoin('initiative_types', 'initiatives.initiative_type_id', '=', 'initiative_types.id')
            ->whereIn('initiative_pipeline_stage_id', $this->wonStageIds)
            ->whereBetween('initiatives.created_at', [$this->startDate, $this->endDate])
            ->groupBy('initiative_type_id')
            ->get();
    }

    /**
     * Retrieves open initiatives by states.
     */
    public function getOpenInitiativesByStates()
    {
        return $this->initiativeRepository
            ->resetModel()
            ->select(
                'initiative_pipeline_stages.name',
                DB::raw('COUNT(initiative_value) as total')
            )
            ->leftJoin('initiative_pipeline_stages', 'initiatives.initiative_pipeline_stage_id', '=', 'initiative_pipeline_stages.id')
            ->whereNotIn('initiative_pipeline_stage_id', $this->wonStageIds)
            ->whereNotIn('initiative_pipeline_stage_id', $this->lostStageIds)
            ->whereBetween('initiatives.created_at', [$this->startDate, $this->endDate])
            ->groupBy('initiative_pipeline_stage_id')
            ->orderByDesc('total')
            ->get();
    }

    /**
     * Returns over time stats.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $valueColumn
     * @param  string  $period
     */
    public function getOverTimeStats($startDate, $endDate, $valueColumn, $dateColumn = 'created_at', $period = 'auto'): array
    {
        $config = $this->getTimeInterval($startDate, $endDate, $dateColumn, $period);

        $groupColumn = $config['group_column'];

        $query = $this->initiativeRepository
            ->resetModel()
            ->select(
                DB::raw("$groupColumn AS date"),
                DB::raw(DB::getTablePrefix()."$valueColumn AS total"),
                DB::raw('COUNT(*) AS count')
            )
            ->whereIn('initiative_pipeline_stage_id', $this->stageIds)
            ->whereBetween($dateColumn, [$startDate, $endDate])
            ->groupBy('date');

        if (! empty($stageIds)) {
            $query->whereIn('initiative_pipeline_stage_id', $stageIds);
        }

        $results = $query->get();

        foreach ($config['intervals'] as $interval) {
            $total = $results->where('date', $interval['filter'])->first();

            $stats[] = [
                'label' => $interval['start'],
                'total' => $total?->total ?? 0,
                'count' => $total?->count ?? 0,
            ];
        }

        return $stats ?? [];
    }
}
