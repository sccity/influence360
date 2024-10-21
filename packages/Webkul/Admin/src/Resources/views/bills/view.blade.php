<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.bills.view.title', ['bill_number' => $bill->bill_number])
    </x-slot>

    <!-- Content -->
    <div class="flex gap-4">
        <!-- Left Panel -->
        {!! view_render_event('admin.bills.view.left.before', ['bill' => $bill]) !!}

        <div class="sticky top-[73px] flex min-w-[394px] max-w-[394px] flex-col self-start rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <!-- Bill Information -->
            <div class="flex w-full flex-col gap-2 border-b border-gray-200 p-4 dark:border-gray-800">
                <!-- Breadcrumbs -->
                <div class="flex items-center justify-between">
                    <x-admin::breadcrumbs
                        name="bills.view"
                        :entity="$bill"
                    />
                </div>

                <!-- Title -->
                <div class="mb-4 flex flex-col gap-0.5">
                    {!! view_render_event('admin.bills.view.title.before', ['bill' => $bill]) !!}

                    <h3 class="text-lg font-bold dark:text-white">
                        {{ $bill->short_title }}
                    </h3>

                    {!! view_render_event('admin.bills.view.title.after', ['bill' => $bill]) !!}
                </div>
                
                <!-- Activity Actions -->
                <div class="flex flex-wrap gap-2">
                    {!! view_render_event('admin.bills.view.actions.before', ['bill' => $bill]) !!}

                    <!-- Mail Activity Action -->
                    <x-admin::activities.mail-activity
                        :entity="$bill"
                        entity-control-name="bill_id"
                    />

                    <!-- File Activity Action -->
                    <x-admin::activities.actions.file
                        :entity="$bill"
                        entity-control-name="bill_id"
                    />

                    <!-- Note Activity Action -->
                    <x-admin::activities.actions.note
                        :entity="$bill"
                        entity-control-name="bill_id"
                    />

                    <!-- Activity Action -->
                    <x-admin::activities.actions.activity
                        :entity="$bill"
                        entity-control-name="bill_id"
                    />

                    {!! view_render_event('admin.bills.view.actions.after', ['bill' => $bill]) !!}
                </div>
            </div>

            <!-- Bill Attributes -->
            @include('admin::bills.view.bill-details')

            <!-- Additional sections if needed -->
        </div>

        {!! view_render_event('admin.bills.view.left.after', ['bill' => $bill]) !!}

        <!-- Right Panel -->
        <div class="flex w-full flex-col gap-4 rounded-lg">
            {!! view_render_event('admin.bills.view.right.before', ['bill' => $bill]) !!}

            <!-- Activities -->
            <x-admin::activities 
                :entity-id="$bill->id" 
                entity-type="Webkul\Bills\Models\Bill"
                :endpoint="route('admin.bills.activities.index', $bill->id)"
            />

            {!! view_render_event('admin.bills.view.right.after', ['bill' => $bill]) !!}
        </div>
    </div>
</x-admin::layouts>

@push('scripts')
    <script>
        function deleteConfirmation(message) {
            if (!confirm(message)) {
                return;
            }

            $.ajax({
                type: 'DELETE',
                url: '{{ route('admin.bills.delete', $bill->id) }}',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function (data) {
                    window.location.href = '{{ route('admin.bills.index') }}';
                }
            });
        }
    </script>
@endpush
