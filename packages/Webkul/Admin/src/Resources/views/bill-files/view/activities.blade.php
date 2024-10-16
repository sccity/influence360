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

{!! view_render_event('admin.bill-files.view.activities.before', ['billFile' => $billFile]) !!}

<x-admin::activities 
    :entity-id="$billFile->id" 
    entity-type="Webkul\BillFile\Models\BillFile"
    :endpoint="route('admin.bill-files.activities.index', $billFile->id)"
/>

{!! view_render_event('admin.bill-files.view.activities.after', ['billFile' => $billFile]) !!}
