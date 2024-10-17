<?php

namespace Webkul\Admin\Http\Controllers\BillFiles;

use Webkul\Activity\Repositories\ActivityRepository;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Resources\ActivityResource;
use Webkul\Email\Repositories\EmailRepository;

class ActivityController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
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
        $activities = $this->activityRepository
            ->leftJoin('bill_file_activities', 'activities.id', '=', 'bill_file_activities.activity_id')
            ->where('bill_file_activities.bill_file_id', $id)
            ->get();

        return ActivityResource::collection($this->concatEmail($activities));
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

    public function store()
    {
        $this->validate(request(), [
            'type'          => 'required',
            'title'         => 'required_if:type,email',
            'comment'       => 'required_if:type,email',
            'schedule_from' => 'required_if:type,email',
        ]);

        Event::dispatch('activity.create.before');

        $activity = $this->activityRepository->create(array_merge(request()->all(), [
            'schedule_to' => request('schedule_from'), // For email activities, set schedule_to same as schedule_from
            'is_done'     => 1, // Emails are always marked as done
            'user_id'     => auth()->guard('user')->user()->id,
        ]));

        if (request()->has('participants')) {
            foreach (request('participants')['users'] ?? [] as $userId) {
                $activity->participants()->create([
                    'user_id' => $userId,
                ]);
            }

            foreach (request('participants')['persons'] ?? [] as $personId) {
                $activity->participants()->create([
                    'person_id' => $personId,
                ]);
            }
        }

        Event::dispatch('activity.create.after', $activity);

        return new ActivityResource($activity);
    }
}
