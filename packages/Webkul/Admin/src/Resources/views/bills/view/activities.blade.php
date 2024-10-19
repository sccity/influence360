@php
    $activityTypes = [
        'all'       => 'All',
        'note'      => 'Note',
        'call'      => 'Call',
        'meeting'   => 'Meeting',
        'lunch'     => 'Lunch',
        'email'     => 'Email',
        'task'      => 'Task',
    ];
@endphp

{!! view_render_event('admin.bills.view.activities.before', ['bill' => $bill]) !!}

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">@lang('admin::app.dashboard.index.all-activities')</h2>
        <a href="{{ route('admin.activities.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 underline">View All</a>
    </div>
    <div class="border-t dark:border-gray-700 pt-4">
        <x-admin::activities 
            :endpoint="route('admin.dashboard.activities')"
        />
    </div>
</div>

{!! view_render_event('admin.bills.view.activities.after', ['bill' => $bill]) !!}
