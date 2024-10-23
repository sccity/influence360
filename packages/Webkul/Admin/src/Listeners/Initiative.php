<?php

namespace Webkul\Admin\Listeners;

use Webkul\Activity\Repositories\ActivityRepository;
use Webkul\Initiative\Events\InitiativeCreated;
use Webkul\Initiative\Contracts\Initiative as InitiativeContract;
use Illuminate\Support\Facades\Log;

class Initiative
{
    /**
     * Create a new listener instance.
     *
     * @param  ActivityRepository  $activityRepository
     * @return void
     */
    public function __construct(protected ActivityRepository $activityRepository) {}

    /**
     * Handle the event.
     *
     * @param  InitiativeCreated|InitiativeContract  $event
     * @return void
     */
    public function handle($event)
    {
        $initiative = $event instanceof InitiativeCreated ? $event->initiative : $event;
        $this->processInitiative($initiative);
    }

    /**
     * Handle the event.
     *
     * @param  InitiativeCreated|InitiativeContract  $event
     * @return void
     */
    public function __invoke($event)
    {
        $initiative = $event instanceof InitiativeCreated ? $event->initiative : $event;
        $this->processInitiative($initiative);
    }

    /**
     * Process the created initiative.
     *
     * @param  InitiativeContract  $initiative
     * @return void
     */
    protected function processInitiative(InitiativeContract $initiative)
    {
        // Your logic here
        Log::info('Initiative processed: ' . $initiative->id);
    }

    /**
     * @param  \Webkul\Initiative\Contracts\Initiative  $initiative
     * @return void
     */
    public function linkToMailActivity($initiative)
    {
        if (! request('activity_id')) {
            return;
        }

        try {
            $activity = $this->activityRepository->find(request('activity_id'));
            
            if ($activity && $activity->type === 'mail') {
                $activity->initiatives()->attach($initiative->id);
                Log::info('Linked mail activity to initiative:', ['initiative_id' => $initiative->id, 'activity_id' => $activity->id]);
            }
        } catch (\Exception $e) {
            Log::error('Error linking mail activity to initiative:', ['initiative_id' => $initiative->id, 'activity_id' => request('activity_id'), 'error' => $e->getMessage()]);
        }
    }
}
