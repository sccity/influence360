<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.bill-files.view.title', ['name' => $billFile->name])
    </x-slot>

    <!-- Content -->
    <div class="flex gap-4">
        <!-- Left Panel -->
        <?php echo view_render_event('admin.bill-files.view.left.before', ['billFile' => $billFile]); ?>

        <div class="sticky top-[73px] flex min-w-[394px] max-w-[394px] flex-col self-start rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <!-- Bill File Information -->
            <div class="flex w-full flex-col gap-2 border-b border-gray-200 p-4 dark:border-gray-800">
                <!-- Breadcrumbs -->
                <div class="flex items-center justify-between">
                    <x-admin::breadcrumbs
                        name="bill-files.view"
                        :entity="$billFile"
                    />
                </div>

                <!-- Title -->
                <div class="mb-4 flex flex-col gap-0.5">
                    {!! view_render_event('admin.bill-files.view.title.before', ['billFile' => $billFile]) !!}

                    <h3 class="text-lg font-bold dark:text-white">
                        {{ $billFile->name }}
                    </h3>

                    <p class="dark:text-white">
                        {{ $billFile->billid }}
                    </p>

                    {!! view_render_event('admin.bill-files.view.title.after', ['billFile' => $billFile]) !!}
                </div>
                
                <!-- Activity Actions -->
                <div class="flex flex-wrap gap-2">
                    {!! view_render_event('admin.bill-files.view.actions.before', ['billFile' => $billFile]) !!}

                    <!-- Mail Activity Action -->
                    @include('admin::bill-files.components.mail-activity', [
                        'entity' => $billFile,
                        'entityControlName' => 'bill_file_id'
                    ])

                    <!-- File Activity Action -->
                    <x-admin::activities.actions.file
                        :entity="$billFile"
                        entity-control-name="bill_file_id"
                    />

                    <!-- Note Activity Action -->
                    <x-admin::activities.actions.note
                        :entity="$billFile"
                        entity-control-name="bill_file_id"
                    />

                    <!-- Activity Action -->
                    <x-admin::activities.actions.activity
                        :entity="$billFile"
                        entity-control-name="bill_file_id"
                    />

                    {!! view_render_event('admin.bill-files.view.actions.after', ['billFile' => $billFile]) !!}
                </div>
            </div>

            <!-- Bill Attributes -->
            @include('admin::bill-files.view.attributes')

            <!-- Additional sections if needed -->
        </div>

        {!! view_render_event('admin.bill-files.view.left.after', ['billFile' => $billFile]) !!}

        <!-- Right Panel -->
        <div class="flex w-full flex-col gap-4 rounded-lg">
            {!! view_render_event('admin.bill-files.view.right.before', ['billFile' => $billFile]) !!}

            <!-- Activities -->
            @include('admin::bill-files.view.activities')

            {!! view_render_event('admin.bill-files.view.right.after', ['billFile' => $billFile]) !!}
        </div>
    </div>
</x-admin::layouts>

@pushOnce('scripts')
    <script>
        function deleteConfirmation(message) {
            if (!confirm(message)) {
                return;
            }

            $.ajax({
                type: 'DELETE',
                url: '{{ route('admin.bill-files.delete', $billFile->id) }}',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function (data) {
                    window.location.href = '{{ route('admin.bill-files.index') }}';
                }
            });
        }
    </script>
@endPushOnce
