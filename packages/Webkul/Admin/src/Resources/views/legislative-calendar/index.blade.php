<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.legislative-calendar.index.title')
    </x-slot>

    <x-slot:header>
        <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css' rel='stylesheet' />
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js'></script>
        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <script src="https://unpkg.com/tippy.js@6"></script>
    </x-slot:header>

    <!-- Header -->
    {!! view_render_event('admin.legislative-calendar.index.header.before') !!}

    <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
        <div class="flex flex-col gap-2">
            <div class="flex cursor-pointer items-center">
                <x-admin::breadcrumbs name="legislative-calendar" />
            </div>
        </div>
    </div>

    {!! view_render_event('admin.legislative-calendar.index.header.after') !!}

    {!! view_render_event('admin.legislative-calendar.index.content.before') !!}

    <!-- Content -->
    <div class="mt-3.5">
        <div id='calendar' class="bg-white p-4 rounded-lg shadow"></div>
    </div>

    {!! view_render_event('admin.legislative-calendar.index.content.after') !!}

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: {
                    url: '{{ route('admin.legislative-calendar.get-events') }}',
                    method: 'GET'
                },
                eventClick: function(info) {
                    if (info.event.url) {
                        window.location.href = info.event.url;
                        return false;
                    }
                },
                eventDidMount: function(info) {
                    if (typeof tippy === 'function') {
                        tippy(info.el, {
                            content: info.event.extendedProps.description + '<br>Location: ' + info.event.extendedProps.location,
                            placement: 'top',
                            trigger: 'mouseenter',
                            interactive: true,
                            allowHTML: true
                        });
                    }
                }
            });
            calendar.render();
        });
    </script>
    @endpush
</x-admin::layouts>
