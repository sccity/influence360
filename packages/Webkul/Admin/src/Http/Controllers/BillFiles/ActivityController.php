<?php

namespace Webkul\Admin\Http\Controllers\BillFiles;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Activity\Repositories\ActivityRepository;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Resources\ActivityResource;
use Webkul\Email\Repositories\EmailRepository;
use Webkul\BillFiles\Repositories\BillFileRepository;

class ActivityController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected BillFileRepository $billFileRepository,
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
        $billFile = $this->billFileRepository->findOrFail($id);
    
        $activities = $billFile->activities()
            ->with(['user', 'participants', 'files'])
            ->orderByDesc('created_at')
            ->get();
    
        return ActivityResource::collection($activities);
    }
    
    

    /**
     * Concatenate email and sort activities.
     *
     * @param  \Illuminate\Support\Collection  $activities
     * @return \Illuminate\Support\Collection
     */
    protected function concatEmail($activities)
    {
        return $activities->sortByDesc('id')->sortByDesc('created_at');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'type'          => 'required',
            'title'         => 'required_if:type,email',
            'comment'       => 'required_if:type,email,note',
            'schedule_from' => 'required_unless:type,note,file',
            'schedule_to'   => 'required_unless:type,note,file,email',
        ]);

        Event::dispatch('activity.create.before');

        $data = request()->all();

        if ($data['type'] === 'email') {
            $data['schedule_to'] = $data['schedule_from'];
            $data['is_done'] = 1;
        }

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

        Event::dispatch('activity.create.after', $activity);

        return new JsonResponse([
            'message' => trans('admin::app.activities.create-success'),
            'data'    => new ActivityResource($activity),
        ]);
    }

    public function storeBillFileMailActivity(): JsonResponse
    {
        $this->validate(request(), [
            'title'         => 'required',
            'comment'       => 'required',
            'schedule_from' => 'required|date',
            'bill_file_id'  => 'required|exists:bill_files,id',
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

        // Create the bill file activity relationship
        $activity->billFiles()->attach($data['bill_file_id']);

        Event::dispatch('activity.create.after', $activity);

        return new JsonResponse([
            'message' => trans('admin::app.activities.create-success'),
            'data'    => new ActivityResource($activity),
        ]);
    }
}
