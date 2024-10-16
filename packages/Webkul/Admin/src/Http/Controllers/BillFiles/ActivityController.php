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
}

