<?php

namespace Webkul\Admin\Http\Controllers\Initiative;

use Illuminate\Support\Facades\DB;
use Webkul\Activity\Repositories\ActivityRepository;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Resources\ActivityResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Initiative\Repositories\InitiativeRepository;
use Illuminate\Support\Facades\Log;

class ActivityController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ActivityRepository $activityRepository,
        protected InitiativeRepository $initiativeRepository
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $initiative = $this->initiativeRepository->findOrFail($id);
        
        $activities = $initiative->activities()
            ->with(['user', 'participants', 'files'])
            ->orderByDesc('created_at')
            ->get();
        
        Log::info('Initiative ID: ' . $id);
        Log::info('Activities count: ' . $activities->count());
        Log::info('Activities for initiative ' . $id . ':', $activities->toArray());
        
        if ($activities->isEmpty()) {
            Log::warning('No activities found for initiative ' . $id);
            return response()->json(['data' => []], 200);
        }
        
        return ActivityResource::collection($activities);
    }

    public function store(): JsonResponse
    {
        Log::info('Received request data for initiative activity:', request()->all());

        $this->validate(request(), [
            'type'          => 'required',
            'title'         => 'required_if:type,email',
            'comment'       => 'required_if:type,email,note',
            'schedule_from' => 'required_unless:type,note,file',
            'schedule_to'   => 'required_unless:type,note,file,email',
            'initiative_id' => 'required|exists:initiatives,id',
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

        // Attach the activity to the initiative
        $this->initiativeRepository->attachActivity($data['initiative_id'], $activity->id);
        Log::info('Attached initiative to activity:', ['initiative_id' => $data['initiative_id'], 'activity_id' => $activity->id]);

        Event::dispatch('activity.create.after', $activity);

        return new JsonResponse([
            'message' => trans('admin::app.activities.create-success'),
            'data'    => new ActivityResource($activity),
        ]);
    }

    public function storeInitiativeMailActivity(): JsonResponse
    {
        $this->validate(request(), [
            'title'         => 'required',
            'comment'       => 'required',
            'schedule_from' => 'required|date',
            'initiative_id' => 'required|exists:initiatives,id',
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

        // Attach the activity to the initiative
        $this->initiativeRepository->attachActivity($data['initiative_id'], $activity->id);

        Event::dispatch('activity.create.after', $activity);

        return new JsonResponse([
            'message' => trans('admin::app.activities.create-success'),
            'data'    => new ActivityResource($activity),
        ]);
    }
}
