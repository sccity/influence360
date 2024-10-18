<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.bill-files.index.title')
    </x-slot>

    <!-- Header -->
    {!! view_render_event('admin.bill-files.index.header.before') !!}

    <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
        {!! view_render_event('admin.bill-files.index.header.left.before') !!}

        <div class="flex flex-col gap-2">
            <div class="flex cursor-pointer items-center">
                <!-- Breadcrumbs -->
                <x-admin::breadcrumbs name="bill-files" />
            </div>

            <div class="text-xl font-bold dark:text-white">
                @lang('admin::app.bill-files.index.title')
            </div>
        </div>

        {!! view_render_event('admin.bill-files.index.header.left.after') !!}

        {!! view_render_event('admin.bill-files.index.header.right.before') !!}

        <div class="flex items-center gap-x-2.5">
            <!-- Create button for Bill Files (if needed) -->
           
        </div>

        {!! view_render_event('admin.bill-files.index.header.right.after') !!}
    </div>

    {!! view_render_event('admin.bill-files.index.header.after') !!}

    {!! view_render_event('admin.bill-files.index.content.before') !!}

    <!-- Content -->
    <div class="mt-3.5">
        <x-admin::datagrid src="{{ route('admin.bill-files.index') }}" />
    </div>

    {!! view_render_event('admin.bill-files.index.content.after') !!}

    @push('scripts')
        <script>
            function toggleTracked(id, isChecked) {
                fetch("{{ route('admin.bill-files.toggle-tracked', ':id') }}".replace(':id', id), {
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
                        // Emit a custom event for success notification
                        window.addFlash({
                            type: 'success',
                            message: data.message
                        });
                    } else {
                        // Emit a custom event for error notification
                        window.eventBus.emit('add-flash', {
                            type: 'error',
                            message: data.message || "@lang('admin::app.bill-files.notifications.error')"
                        });
                        document.getElementById('is_tracked_' + id).checked = !isChecked;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Emit a custom event for error notification
                    window.eventBus.emit('add-flash', {
                        type: 'error',
                        message: "@lang('admin::app.bill-files.notifications.error')"
                    });
                    document.getElementById('is_tracked_' + id).checked = !isChecked;
                });
            }
        </script>
    @endpush
</x-admin::layouts>
