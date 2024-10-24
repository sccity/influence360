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

        $entities = [
            'initiative_id' => $this->initiativeRepository,
            'person_id' => $this->personRepository,
            'warehouse_id' => $this->warehouseRepository,
            'product_id' => $this->productRepository,
            'bill_file_id' => $this->billFileRepository,
            'bill_id' => $this->billRepository
        ];

        foreach ($entities as $key => $repository) {
            if ($entityId = request()->input($key)) {
                try {
                    $entity = $repository->find($entityId);
                    if ($entity && !$entity->activities->contains($activity->id)) {
                        $entity->activities()->attach($activity->id);
                        Log::info("Attached activity to {$key}:", ['entity_id' => $entityId, 'activity_id' => $activity->id]);
                    }
                } catch (\Exception $e) {
                    Log::error("Error attaching activity to {$key}:", ['entity_id' => $entityId, 'activity_id' => $activity->id, 'error' => $e->getMessage()]);
                }
            }
        }
    }
}
