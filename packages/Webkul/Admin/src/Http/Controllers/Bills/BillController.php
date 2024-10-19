<?php

namespace Webkul\Admin\Http\Controllers\Bills;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Bills\Repositories\BillRepository;
use Webkul\Admin\DataGrids\Bills\BillDataGrid;
use Webkul\Admin\DataGrids\Bills\TrackedBillsDataGrid;

class BillController extends Controller
{
    public function __construct(protected BillRepository $billRepository)
    {
    }

    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return app(BillDataGrid::class)->toJson();
        }

        $isTracked = request()->get('is_tracked');
        return view('admin::bills.index', compact('isTracked'));
    }

    public function create()
    {
        return view('admin::bills.create');
    }

    public function store()
    {
        // Implement store logic
    }

    public function view($id)
    {
        $bill = $this->billRepository->findOrFail($id);

        return view('admin::bills.view', compact('bill'));
    }

    public function edit($id)
    {
        $bill = $this->billRepository->findOrFail($id);

        return view('admin::bills.edit', compact('bill'));
    }

    public function update($id)
    {
        // Implement update logic
    }

    public function destroy($id)
    {
        try {
            $this->billRepository->delete($id);

            return response()->json(['message' => trans('admin::app.bills.delete-success')]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function massDestroy()
    {
        $billIds = request('ids');

        foreach ($billIds as $billId) {
            $this->billRepository->delete($billId);
        }

        return response()->json(['message' => trans('admin::app.bills.mass-delete-success')]);
    }

    public function toggleTracked($id)
    {
        $bill = $this->billRepository->findOrFail($id);
        $bill->is_tracked = !$bill->is_tracked;
        $bill->save();

        return response()->json([
            'success' => true,
            'message' => $bill->is_tracked 
                ? trans('admin::app.bills.notifications.tracked')
                : trans('admin::app.bills.notifications.untracked'),
            'is_tracked' => $bill->is_tracked
        ]);
    }

    public function trackedBills()
    {
        return view('admin::bills.tracked');
    }

    public function getTrackedBills(): JsonResponse
    {
        return app(TrackedBillsDataGrid::class)->toJson();
    }
}
