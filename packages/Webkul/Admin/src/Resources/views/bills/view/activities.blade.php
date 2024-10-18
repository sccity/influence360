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

<x-admin::activities 
    :entity-id="$bill->id" 
    entity-type="Webkul\Bills\Models\Bill"
    :endpoint="route('admin.bills.activities.index', $bill->id)"
/>

{!! view_render_event('admin.bills.view.activities.after', ['bill' => $bill]) !!}

