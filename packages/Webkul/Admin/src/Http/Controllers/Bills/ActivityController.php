<?php

namespace Webkul\Admin\Http\Controllers\Bills;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Activity\Repositories\ActivityRepository;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Resources\ActivityResource;
use Webkul\Email\Repositories\EmailRepository;
use Webkul\Bills\Repositories\BillRepository;
use Illuminate\Support\Facades\Log;

class ActivityController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected BillRepository $billRepository,
        protected ActivityRepository $activityRepository,
        protected EmailRepository $emailRepository
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index($id)
    {
        $bill = $this->billRepository->findOrFail($id);
    
        $activities = $bill->activities()
            ->with(['user', 'participants', 'files'])
            ->orderByDesc('created_at')
            ->get();
    
        return ActivityResource::collection($activities);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(): JsonResponse
    {
        Log::info('Received request data for bill activity:', request()->all());

        $this->validate(request(), [
            'type'          => 'required',
            'title'         => 'required_if:type,email',
            'comment'       => 'required_if:type,email,note',
            'schedule_from' => 'required_unless:type,note,file',
            'schedule_to'   => 'required_unless:type,note,file,email',
            'bill_id'       => 'required|exists:bills,id',
        ]);

        Event::dispatch('activity.create.before');

        $data = request()->all();

        if ($data['type'] === 'email') {
            $data['schedule_to'] = $data['schedule_from'];
            $data['is_done'] = 1;
        }

        $activity = $this->activityRepository->create($data);
        Log::info('Created activity:', $activity->toArray());

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
        Log::info('Attached bill to activity:', ['bill_id' => $data['bill_id'], 'activity_id' => $activity->id]);

        Event::dispatch('activity.create.after', $activity);

        return new JsonResponse([
            'message' => trans('admin::app.activities.create-success'),
            'data'    => new ActivityResource($activity),
        ]);
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

        $activity->bills()->attach($data['bill_id']);

        Event::dispatch('activity.create.after', $activity);

        return new JsonResponse([
            'message' => trans('admin::app.activities.create-success'),
            'data'    => new ActivityResource($activity),
        ]);
    }
}
