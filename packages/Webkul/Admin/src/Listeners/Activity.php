<?php

namespace Webkul\Admin\Listeners;

use Webkul\Activity\Contracts\Activity as ActivityContract;
use Webkul\Contact\Repositories\PersonRepository;
use Webkul\Lead\Repositories\LeadRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Warehouse\Repositories\WarehouseRepository;
use Webkul\BillFiles\Repositories\BillFileRepository;

class Activity
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected LeadRepository $leadRepository,
        protected PersonRepository $personRepository,
        protected ProductRepository $productRepository,
        protected WarehouseRepository $warehouseRepository,
        protected BillFileRepository $billFileRepository
    ) {}

    /**
     * Link activity to lead or person.
     */
    public function afterUpdateOrCreate(ActivityContract $activity): void
    {
        if (request()->input('lead_id')) {
            $lead = $this->leadRepository->find(request()->input('lead_id'));

            if (! $lead->activities->contains($activity->id)) {
                $lead->activities()->attach($activity->id);
            }
        } elseif (request()->input('person_id')) {
            $person = $this->personRepository->find(request()->input('person_id'));

            if (! $person->activities->contains($activity->id)) {
                $person->activities()->attach($activity->id);
            }
        } elseif (request()->input('warehouse_id')) {
            $warehouse = $this->warehouseRepository->find(request()->input('warehouse_id'));

            if (! $warehouse->activities->contains($activity->id)) {
                $warehouse->activities()->attach($activity->id);
            }
        } elseif (request()->input('product_id')) {
            $product = $this->productRepository->find(request()->input('product_id'));

            if (! $product->activities->contains($activity->id)) {
                $product->activities()->attach($activity->id);
            }
        } elseif (request()->input('bill_file_id')) {
            $billFile = $this->billFileRepository->find(request()->input('bill_file_id'));

            if (! $billFile->activities->contains($activity->id)) {
                $billFile->activities()->attach($activity->id);
            }
        }
    }
}
