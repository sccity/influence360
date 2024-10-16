<?php

namespace Webkul\Admin\Http\Controllers\BillFiles;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\BillFiles\Repositories\BillFileRepository;
use Webkul\Admin\DataGrids\BillFiles\BillFileDataGrid;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Resources\ActivityCollection;
use Webkul\Activity\Repositories\ActivityRepository;

class BillFileController extends Controller
{
    public function __construct(protected BillFileRepository $billFileRepository)
    {
    }

    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return app(BillFileDataGrid::class)->toJson();
        }

        return view('admin::bill-files.index');
    }

    public function create(): View
    {
        return view('admin::bill-files.create');
    }

    public function store()
    {
        $this->validate(request(), [
            'billid' => 'required|unique:bill_files,billid',
            'name' => 'required',
            // Add other validation rules as needed
        ]);

        Event::dispatch('bill_file.create.before');

        $billFile = $this->billFileRepository->create(request()->all());

        Event::dispatch('bill_file.create.after', $billFile);

        session()->flash('success', trans('admin::app.bill-files.create-success'));

        return redirect()->route('admin.bill-files.index');
    }

    public function view($id)
    {
        $billFile = $this->billFileRepository->findOrFail($id);

        return view('admin::bill-files.view', compact('billFile'));
    }

    public function edit($id): View
    {
        $billFile = $this->billFileRepository->findOrFail($id);

        return view('admin::bill-files.edit', compact('billFile'));
    }

    public function update($id)
    {
        $this->validate(request(), [
            'billid' => 'required|unique:bill_files,billid,' . $id,
            'name' => 'required',
            // Add other validation rules as needed
        ]);

        Event::dispatch('bill_file.update.before', $id);

        $billFile = $this->billFileRepository->update(request()->all(), $id);

        Event::dispatch('bill_file.update.after', $billFile);

        session()->flash('success', trans('admin::app.bill-files.update-success'));

        return redirect()->route('admin.bill-files.index');
    }

    public function destroy($id): JsonResponse
    {
        Event::dispatch('bill_file.delete.before', $id);

        $this->billFileRepository->delete($id);

        Event::dispatch('bill_file.delete.after', $id);

        return response()->json([
            'message' => trans('admin::app.bill-files.delete-success'),
        ]);
    }

    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $billFileIds = $massDestroyRequest->input('indices');

        foreach ($billFileIds as $billFileId) {
            Event::dispatch('bill_file.delete.before', $billFileId);

            $this->billFileRepository->delete($billFileId);

            Event::dispatch('bill_file.delete.after', $billFileId);
        }

        return response()->json([
            'message' => trans('admin::app.bill-files.mass-delete-success'),
        ]);
    }

    public function activities($id)
    {
        $billFile = $this->billFileRepository->findOrFail($id);

        $activities = $this->activityRepository
            ->leftJoin('bill_file_activities', 'activities.id', '=', 'bill_file_activities.activity_id')
            ->where('bill_file_activities.bill_file_id', $id)
            ->get();

        return ActivityResource::collection($this->concatEmail($activities));
    }

    protected function concatEmail($activities)
    {
        return $activities->sortByDesc('id')->sortByDesc('created_at');
    }

    public function toggleTracked($id): JsonResponse
    {
        try {
            $billFile = $this->billFileRepository->findOrFail($id);
            $billFile->is_tracked = !$billFile->is_tracked;
            $billFile->save();

            return response()->json([
                'success' => true,
                'message' => $billFile->is_tracked 
                    ? trans('admin::app.bill-files.notifications.tracked')
                    : trans('admin::app.bill-files.notifications.untracked'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => trans('admin::app.bill-files.notifications.error'),
            ], 500);
        }
    }
}
