<?php

namespace Webkul\Activity\Repositories;

use Illuminate\Container\Container;
use Webkul\Core\Eloquent\Repository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ActivityRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected FileRepository $fileRepository,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'Webkul\Activity\Contracts\Activity';
    }

    /**
     * Create activity.
     *
     * @return \Webkul\Activity\Contracts\Activity
     */
    public function create(array $data)
    {
        DB::beginTransaction();

        // Clean up unnecessary fields
        unset($data['attachments']);

        // Add logging before preparation
        \Log::info('Before prepareData - data:', $data);

        $data = $this->prepareData($data);

        // Add logging after preparation
        \Log::info('After prepareData - data:', $data);

        $activity = parent::create($data);

        $this->handleFileUpload($activity, $data);
        $this->handleParticipants($activity, $data);

        if (isset($data['initiative_id'])) {
            $activity->initiatives()->attach($data['initiative_id']);
        }
        if (isset($data['bill_id'])) {
            $activity->bills()->attach($data['bill_id']);
        }

        DB::commit();

        return $activity->load(['user', 'participants', 'files']);
    }

    /**
     * Update activity.
     *
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Activity\Contracts\Activity
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        DB::beginTransaction();

        try {
            $activity = parent::update($data, $id);

            $this->handleFileUpload($activity, $data);
            $this->handleParticipants($activity, $data);

            DB::commit();

            return $activity;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Handle file upload for activity.
     *
     * @param  \Webkul\Activity\Contracts\Activity  $activity
     * @param  array  $data
     * @return void
     */
    protected function handleFileUpload($activity, $data)
    {
        if (isset($data['files']) && is_array($data['files'])) {
            foreach ($data['files'] as $file) {
                $this->fileRepository->upload($file, $activity);
            }
        }
    }

    /**
     * Handle participants for activity.
     *
     * @param  \Webkul\Activity\Contracts\Activity  $activity
     * @param  array  $data
     * @return void
     */
    protected function handleParticipants($activity, $data)
    {
        if (isset($data['participants'])) {
            foreach ($data['participants'] as $participantType => $participantIds) {
                foreach ($participantIds as $participantId) {
                    $activity->participants()->create([
                        $participantType === 'users' ? 'user_id' : 'person_id' => $participantId,
                    ]);
                }
            }
        }
    }

    /**
     * Get activities within a date range.
     *
     * @param  array  $dateRange
     * @return mixed
     */
    public function getActivities($dateRange)
    {
        return $this->select(
            'activities.id',
            'activities.created_at',
            'activities.title',
            'activities.schedule_from as start',
            'activities.schedule_to as end',
            'users.name as user_name',
        )
            ->addSelect(DB::raw('IF(activities.is_done, "done", "") as class'))
            ->leftJoin('activity_participants', 'activities.id', '=', 'activity_participants.activity_id')
            ->leftJoin('users', 'activities.user_id', '=', 'users.id')
            ->whereIn('type', ['call', 'meeting', 'lunch'])
            ->whereBetween('activities.schedule_from', $dateRange)
            ->where(function ($query) {
                if ($userIds = bouncer()->getAuthorizedUserIds()) {
                    $query->whereIn('activities.user_id', $userIds)
                        ->orWhereIn('activity_participants.user_id', $userIds);
                }
            })
            ->distinct()
            ->get();
    }

    /**
     * Check if duration is overlapping with existing activities.
     *
     * @param  string  $startFrom
     * @param  string  $endFrom
     * @param  array  $participants
     * @param  int|null  $id
     * @return bool
     */
    public function isDurationOverlapping($startFrom, $endFrom, $participants, $id = null)
    {
        $queryBuilder = $this->leftJoin('activity_participants', 'activities.id', '=', 'activity_participants.activity_id')
            ->where(function ($query) use ($startFrom, $endFrom) {
                $query->where([
                    ['activities.schedule_from', '<=', $startFrom],
                    ['activities.schedule_to', '>=', $startFrom],
                ])->orWhere([
                    ['activities.schedule_from', '>=', $startFrom],
                    ['activities.schedule_from', '<=', $endFrom],
                ]);
            })
            ->where(function ($query) use ($participants) {
                if (is_null($participants)) {
                    return;
                }

                if (isset($participants['users'])) {
                    $query->orWhereIn('activity_participants.user_id', $participants['users']);
                }

                if (isset($participants['persons'])) {
                    $query->orWhereIn('activity_participants.person_id', $participants['persons']);
                }
            })
            ->groupBy('activities.id');

        if (! is_null($id)) {
            $queryBuilder->where('activities.id', '!=', $id);
        }

        return $queryBuilder->exists();
    }

    /**
     * Get all activities with pagination.
     *
     * @param  int  $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllActivities($perPage = 10)
    {
        return $this->model->with(['user', 'participants'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get recent activities.
     *
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentActivities($limit = 5)
    {
        return $this->model
            ->with('user')
            ->select('id', 'title', 'type', 'comment', 'user_id', 'created_at')
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Get activities by type.
     *
     * @param  string  $type
     * @param  int  $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getActivitiesByType($type, $perPage = 10)
    {
        return $this->model->where('type', $type)
            ->with(['user', 'participants'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get activities for a specific user.
     *
     * @param  int  $userId
     * @param  int  $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getActivitiesForUser($userId, $perPage = 10)
    {
        return $this->model->where('user_id', $userId)
            ->orWhereHas('participants', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with(['user', 'participants'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Search activities.
     *
     * @param  string  $searchTerm
     * @param  int  $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function searchActivities($searchTerm, $perPage = 10)
    {
        return $this->model->where('title', 'like', "%{$searchTerm}%")
            ->orWhere('comment', 'like', "%{$searchTerm}%")
            ->with(['user', 'participants'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    protected function prepareData(array $data)
    {
        \Log::info('prepareData - Incoming data:', $data);

        $additional = [];

        switch ($data['type']) {
            case 'mail':
                $data['title'] = 'Added mail';
                $additional = [
                    'subject' => $data['title'] ?? 'No Subject',
                    'to' => $data['to'] ?? [],
                    'cc' => $data['cc'] ?? [],
                    'bcc' => $data['bcc'] ?? [],
                    'body' => $data['comment'] ?? '',
                ];
                $data['schedule_to'] = $data['schedule_from'];
                $data['is_done'] = 1;
                break;

            case 'note':
                $data['title'] = 'Added note';
                $additional = [
                    'content' => $data['comment'],
                ];
                break;

            case 'call':
            case 'meeting':
            case 'lunch':
                $data['title'] = 'Added ' . $data['type'];
                $additional = [
                    'duration' => $data['duration'] ?? null,
                    'outcome' => $data['outcome'] ?? null,
                    'attendees' => $data['attendees'] ?? [],
                ];
                break;

            case 'file':
                $data['title'] = 'Added file';
                $additional = [
                    'file_name' => $data['file_name'] ?? 'Unnamed File',
                    'file_size' => $data['file_size'] ?? null,
                    'file_type' => $data['file_type'] ?? null,
                ];
                break;

            case 'system':
                // Check if 'additional' is set
                if (isset($data['additional'])) {
                    // If 'additional' is an array, convert it to JSON
                    if (is_array($data['additional'])) {
                        $data['additional'] = json_encode($data['additional']);
                    }
                    // If 'additional' is already a JSON string, leave it as is
                } else {
                    // If 'additional' is not set, set it to an empty JSON array
                    $data['additional'] = '[]';
                }
                break;

            default:
                // ... any other default cases ...
                break;
        }

        $data['additional'] = json_encode($additional);

        \Log::info('prepareData - Prepared data:', $data);

        return $data;
    }
}
