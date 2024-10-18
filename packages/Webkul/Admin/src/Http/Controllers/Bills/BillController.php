<?php

namespace Webkul\Admin\Http\Controllers\Bills;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Bills\Repositories\BillRepository;
use Webkul\Admin\DataGrids\Bills\BillDataGrid;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Resources\ActivityResource;
use Webkul\Activity\Repositories\ActivityRepository;

class BillController extends Controller
{
    public function __construct(
        protected BillRepository $billRepository,
        protected ActivityRepository $activityRepository 
    ) {}

    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return app(BillDataGrid::class)->toJson();
        }

        return view('admin::bills.index');
    }

    public function create(): View
    {
        return view('admin::bills.create');
    }

    public function store()
    {
        $this->validate(request(), [
            'billid' => 'required|unique:bills,billid',
            'name' => 'required',
            // Add other validation rules as needed
        ]);

        Event::dispatch('bill.create.before');

        $bill = $this->billRepository->create(request()->all());

        Event::dispatch('bill.create.after', $bill);

        session()->flash('success', trans('admin::app.bills.create-success'));

        return redirect()->route('admin.bills.index');
    }

    public function view($id)
    {
        $bill = $this->billRepository->findOrFail($id);

        return view('admin::bills.view', compact('bill'));
    }

    public function edit($id): View
    {
        $bill = $this->billRepository->findOrFail($id);

        return view('admin::bills.edit', compact('bill'));
    }

    public function update($id)
    {
        $this->validate(request(), [
            'billid' => 'required|unique:bills,billid,' . $id,
            'name' => 'required',
            // Add other validation rules as needed
        ]);

        Event::dispatch('bill.update.before', $id);

        $bill = $this->billRepository->update(request()->all(), $id);

        Event::dispatch('bill.update.after', $bill);

        session()->flash('success', trans('admin::app.bills.update-success'));

        return redirect()->route('admin.bills.view', $id);
    }

    public function destroy($id)
    {
        Event::dispatch('bill.delete.before', $id);

        $this->billRepository->delete($id);

        Event::dispatch('bill.delete.after', $id);

        return response()->json([
            'message' => trans('admin::app.bills.delete-success'),
        ]);
    }

    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $billIds = $massDestroyRequest->input('indices');

        foreach ($billIds as $billId) {
            Event::dispatch('bill.delete.before', $billId);

            $this->billRepository->delete($billId);

            Event::dispatch('bill.delete.after', $billId);
        }

        return response()->json([
            'message' => trans('admin::app.bills.mass-delete-success'),
        ]);
    }

    public function activities($id)
    {
        $bill = $this->billRepository->findOrFail($id);
        
        $activities = $bill->activities()
            ->with(['user', 'participants', 'files'])
            ->orderByDesc('created_at')
            ->get();
        
        return ActivityResource::collection($activities);
    }

    public function toggleTracked($id): JsonResponse
    {
        try {
            $bill = $this->billRepository->findOrFail($id);
            $bill->is_tracked = !$bill->is_tracked;
            $bill->save();

            return response()->json([
                'success' => true,
                'message' => $bill->is_tracked 
                    ? trans('admin::app.bills.notifications.tracked')
                    : trans('admin::app.bills.notifications.untracked'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => trans('admin::app.bills.notifications.error'),
            ], 500);
        }
    }
}

