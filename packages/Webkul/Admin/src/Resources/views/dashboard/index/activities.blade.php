<div class="bg-white dark:bg-gray-900 rounded-lg box-shadow">
    <div class="p-4 flex justify-between items-center">
        <p class="text-lg font-bold text-gray-800 dark:text-white">
            @lang('admin::app.dashboard.index.all-activities')
        </p>
    </div>

    <div class="border-t dark:border-gray-800">
        <div class="card mt-4">
            <div class="card-title">
                Recent Activities
                <a href="{{ route('admin.activities.index') }}" class="btn btn-sm btn-primary float-right">View All</a>
            </div>
            <div class="card-body p-0">
                <x-admin::activities 
                    :endpoint="route('admin.dashboard.activities')"
                />
            </div>
        </div>
    </div>
</div>
