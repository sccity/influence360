<?php

namespace Webkul\Admin\Listeners;

use Webkul\Activity\Contracts\Activity as ActivityContract;
use Webkul\Contact\Repositories\PersonRepository;
use Webkul\Initiative\Repositories\InitiativeRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Warehouse\Repositories\WarehouseRepository;
use Webkul\BillFiles\Repositories\BillFileRepository;
use Webkul\Bills\Repositories\BillRepository;
use Illuminate\Support\Facades\Log;

class Activity
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected InitiativeRepository $initiativeRepository,
        protected PersonRepository $personRepository,
        protected ProductRepository $productRepository,
        protected WarehouseRepository $warehouseRepository,
        protected BillFileRepository $billFileRepository,
        protected BillRepository $billRepository
    ) {}

    /**
     * Link activity to initiative, person, warehouse, product, bill file, or bill.
     */
    public function afterUpdateOrCreate(ActivityContract $activity): void
    {
        Log::info('Activity listener triggered:', ['activity_id' => $activity->id, 'request_data' => request()->all()]);

        if (request()->input('initiative_id')) {
            $initiative = $this->initiativeRepository->find(request()->input('initiative_id'));

            if (! $initiative->activities->contains($activity->id)) {
                $initiative->activities()->attach($activity->id);
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
        } elseif (request()->input('bill_id')) {
            $bill = $this->billRepository->find(request()->input('bill_id'));

            if ($bill && ! $bill->activities->contains($activity->id)) {
                $bill->activities()->attach($activity->id);
                Log::info('Attached activity to bill in listener:', ['bill_id' => $bill->id, 'activity_id' => $activity->id]);
            }
        }
    }
}

