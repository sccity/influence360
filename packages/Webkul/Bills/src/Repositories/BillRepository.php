<?php

namespace Webkul\Bills\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Bills\Contracts\Bill;

class BillRepository extends Repository
{
    public function model()
    {
        return Bill::class;
    }

    /**
     * Get tracked bills.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTrackedBills($limit = 5)
    {
        return $this->model()
            ->where('is_tracked', true)
            ->orderBy('ai_impact_rating', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Toggle tracked status of a bill.
     *
     * @param int $id
     * @return Bill
     */
    public function toggleTracked($id)
    {
        $bill = $this->findOrFail($id);
        $bill->is_tracked = !$bill->is_tracked;
        $bill->save();

        return $bill;
    }
}
