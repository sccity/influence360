<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.bill-files.index.title')
    </x-slot>

    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <p class="text-xl text-gray-800 dark:text-white font-bold">
            @lang('admin::app.bill-files.index.title')
        </p>
    </div>

    {!! view_render_event('admin.bill-files.index.before') !!}

    <x-admin::datagrid src="{{ route('admin.bill-files.index') }}" />

    {!! view_render_event('admin.bill-files.index.after') !!}

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
