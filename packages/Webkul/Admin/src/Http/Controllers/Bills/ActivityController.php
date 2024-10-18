<?php

namespace Webkul\Admin\Http\Controllers\Bills;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Activity\Repositories\ActivityRepository;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Resources\ActivityResource;
use Webkul\Email\Repositories\EmailRepository;
use Webkul\Bills\Repositories\BillRepository;

class ActivityController extends Controller
{
    public function __construct(
        protected BillRepository $billRepository,
        protected ActivityRepository $activityRepository,
        protected EmailRepository $emailRepository
    ) {}

    public function index($id)
    {
        $bill = $this->billRepository->findOrFail($id);
    
        $activities = $bill->activities()
            ->with(['user', 'participants', 'files'])
            ->orderByDesc('created_at')
            ->get();
    
        return ActivityResource::collection($activities);
    }

    public function storeBillMailActivity(): JsonResponse
    {
        $this->validate(request(), [
            'title'         => 'required',
            'comment'       => 'required',
            'schedule_from' => 'required|date',
            'bill_id'       => 'required|exists:bills,id',
            'participants'  => 'sometimes|array',
        ]);

        Event::dispatch('activity.create.before');

        $data = request()->all();
        $data['type'] = 'email';
        $data['schedule_to'] = $data['schedule_from'];
        $data['is_done'] = 1;
        $data['user_id'] = auth()->id();

        $activity = $this->activityRepository->create($data);

        if (request()->has('participants')) {
            foreach (request('participants') as $participantType => $participantIds) {
                foreach ($participantIds as $participantId) {
                    $activity->participants()->create([
                        $participantType === 'users' ? 'user_id' : 'person_id' => $participantId,
                    ]);
                }
            }
        }

        // Create the bill activity relationship
        $activity->bills()->attach($data['bill_id']);

        Event::dispatch('activity.create.after', $activity);

        return new JsonResponse([
            'message' => trans('admin::app.activities.create-success'),
            'data'    => new ActivityResource($activity),
        ]);
    }
}

