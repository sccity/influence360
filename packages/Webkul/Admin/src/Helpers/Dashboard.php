<?php

namespace Webkul\Admin\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Webkul\Admin\Helpers\Reporting\Activity;
use Webkul\Admin\Helpers\Reporting\Initiative;
use Webkul\Admin\Helpers\Reporting\Organization;
use Webkul\Admin\Helpers\Reporting\Person;
use Webkul\Admin\Helpers\Reporting\Product;
use Webkul\Admin\Helpers\Reporting\Quote;

class Dashboard
{
    /**
     * Create a controller instance.
     *
     * @return void
     */
    public function __construct(
        protected Initiative $initiativeReporting,
        protected Activity $activityReporting,
        protected Product $productReporting,
        protected Person $personReporting,
        protected Organization $organizationReporting,
        protected Quote $quoteReporting,
    ) {}

    /**
     * Returns the overall revenue statistics.
     */
    public function getRevenueStats(): array
    {
        return [
            'total_won_revenue'  => $this->initiativeReporting->getTotalWonInitiativeValueProgress(),
            'total_lost_revenue' => $this->initiativeReporting->getTotalLostInitiativeValueProgress(),
        ];
    }

    /**
     * Returns the overall statistics.
     */
    public function getOverAllStats(): array
    {
        return [
            'total_initiatives'           => $this->initiativeReporting->getTotalInitiativesProgress(),
            'average_initiative_value'    => $this->initiativeReporting->getAverageInitiativeValueProgress(),
            'average_initiatives_per_day' => $this->initiativeReporting->getAverageInitiativesPerDayProgress(),
            'total_quotations'      => $this->quoteReporting->getTotalQuotesProgress(),
            'total_persons'         => $this->personReporting->getTotalPersonsProgress(),
            'total_organizations'   => $this->organizationReporting->getTotalOrganizationsProgress(),
        ];
    }

    /**
     * Returns initiatives statistics.
     */
    public function getTotalInitiativesStats(): array
    {
        return [
            'all'  => [
                'over_time' => $this->initiativeReporting->getTotalInitiativesOverTime(),
            ],

            'won'  => [
                'over_time' => $this->initiativeReporting->getTotalWonInitiativesOverTime(),
            ],
            'lost' => [
                'over_time' => $this->initiativeReporting->getTotalLostInitiativesOverTime(),
            ],
        ];
    }

    /**
     * Returns initiatives revenue statistics by sources.
     */
    public function getInitiativesStatsBySources(): mixed
    {
        return $this->initiativeReporting->getTotalWonInitiativeValueBySources();
    }

    /**
     * Returns initiatives revenue statistics by types.
     */
    public function getInitiativesStatsByTypes(): mixed
    {
        return $this->initiativeReporting->getTotalWonInitiativeValueByTypes();
    }

    /**
     * Returns open initiatives statistics by states.
     */
    public function getOpenInitiativesByStates(): mixed
    {
        return $this->initiativeReporting->getOpenInitiativesByStates();
    }

    /**
     * Returns top selling products statistics.
     */
    public function getTopSellingProducts(): Collection
    {
        return $this->productReporting->getTopSellingProductsByRevenue(5);
    }

    /**
     * Returns top selling products statistics.
     */
    public function getTopPersons(): Collection
    {
        return $this->personReporting->getTopCustomersByRevenue(5);
    }

    /**
     * Get the start date.
     *
     * @return \Carbon\Carbon
     */
    public function getStartDate(): Carbon
    {
        return $this->initiativeReporting->getStartDate();
    }

    /**
     * Get the end date.
     *
     * @return \Carbon\Carbon
     */
    public function getEndDate(): Carbon
    {
        return $this->initiativeReporting->getEndDate();
    }

    /**
     * Returns date range
     */
    public function getDateRange(): string
    {
        return $this->getStartDate()->format('d M').' - '.$this->getEndDate()->format('d M');
    }
}
