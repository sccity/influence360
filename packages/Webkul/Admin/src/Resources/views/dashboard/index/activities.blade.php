<div class="bg-white dark:bg-gray-900 rounded-lg box-shadow">
    <div class="p-4 flex justify-between items-center">
        <p class="text-lg font-bold text-gray-800 dark:text-white">
            @lang('admin::app.dashboard.index.all-activities')
        </p>
        <a href="{{ route('admin.activities.index') }}" class="text-sm text-blue-600 hover:underline">
            @lang('admin::app.dashboard.index.view-all')
        </a>
    </div>

    <div class="border-t dark:border-gray-800">
        @forelse ($recentActivities as $activity)
            <div class="p-2 flex items-center justify-between border-b last:border-b-0 dark:border-gray-800 text-xs">
                <div class="flex flex-col w-2/3">
                    <span class="font-medium text-gray-800 dark:text-white truncate">{{ $activity->title }}</span>
                    <span class="text-gray-500 dark:text-gray-400 truncate">{{ $activity->type }}</span>
                </div>
                <div class="flex items-center justify-end w-1/3">
                    <span class="text-gray-500 dark:text-gray-400">
                        {{ $activity->schedule_from->diffForHumans() }}
                    </span>
                </div>
            </div>
        @empty
            <p class="p-4 text-gray-500 dark:text-gray-400">@lang('admin::app.dashboard.index.no-activities')</p>
        @endforelse
    </div>
</div>
