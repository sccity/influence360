<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.bills.index.title')
    </x-slot>

    <!-- Header -->
    {!! view_render_event('admin.bills.index.header.before') !!}

    <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
        {!! view_render_event('admin.bills.index.header.left.before') !!}

        <div class="flex flex-col gap-2">
            <div class="flex cursor-pointer items-center">
                <!-- Breadcrumbs -->
                <x-admin::breadcrumbs name="bills" />
            </div>
        </div>

        {!! view_render_event('admin.bills.index.header.left.after') !!}

        {!! view_render_event('admin.bills.index.header.right.before') !!}

        <div class="flex items-center gap-x-2.5">
            <!-- Create button for Bills (if needed) -->
            
        </div>

        {!! view_render_event('admin.bills.index.header.right.after') !!}
    </div>

    {!! view_render_event('admin.bills.index.header.after') !!}

    {!! view_render_event('admin.bills.index.content.before') !!}

    <!-- Content -->
    <div class="mt-3.5">
        <x-admin::datagrid src="{{ route('admin.bills.index') }}" />
    </div>

    {!! view_render_event('admin.bills.index.content.after') !!}

    @push('scripts')
        <script>
            function toggleTracked(id, isChecked) {
                fetch("{{ route('admin.bills.toggle-tracked', ':id') }}".replace(':id', id), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ is_tracked: isChecked })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.addFlash({
                            type: 'success',
                            message: data.message
                        });
                    } else {
                        window.eventBus.emit('add-flash', {
                            type: 'error',
                            message: data.message || "@lang('admin::app.bills.notifications.error')"
                        });
                        document.getElementById('is_tracked_' + id).checked = !isChecked;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.eventBus.emit('add-flash', {
                        type: 'error',
                        message: "@lang('admin::app.bills.notifications.error')"
                    });
                    document.getElementById('is_tracked_' + id).checked = !isChecked;
                });
            }
        </script>
    @endpush
</x-admin::layouts>
