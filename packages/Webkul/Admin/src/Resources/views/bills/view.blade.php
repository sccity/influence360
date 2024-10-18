<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.bills.view.title', ['name' => $bill->name])
    </x-slot>

    <!-- Content -->
    <div class="flex gap-4">
        <!-- Left Panel -->
        {!! view_render_event('admin.bills.view.left.before', ['bill' => $bill]) !!}

        <div class="flex-1">
            <div class="flex flex-col gap-2 mb-4">
                <p class="text-xl text-gray-800 dark:text-white font-bold">
                    {{ $bill->name }}
                </p>

                <p class="text-gray-600 dark:text-gray-300">
                    {{ $bill->billid }}
                </p>
            </div>

            <!-- Bill Information -->
            <div class="bg-white dark:bg-gray-900 rounded box-shadow">
                @include('admin::bills.view.bill-details')
            </div>
        </div>

        {!! view_render_event('admin.bills.view.left.after', ['bill' => $bill]) !!}

        <!-- Right Panel -->
        <div class="w-[400px]">
            {!! view_render_event('admin.bills.view.right.before', ['bill' => $bill]) !!}

            <!-- Activities -->
            <x-admin::accordion>
                <x-slot:header>
                    <p class="p-2.5 text-gray-800 dark:text-white text-base font-semibold">
                        @lang('admin::app.bills.view.activities')
                    </p>
                </x-slot>

                <x-slot:content>
                    @include('admin::bills.view.activities')
                </x-slot>
            </x-admin::accordion>

            {!! view_render_event('admin.bills.view.right.after', ['bill' => $bill]) !!}
        </div>
    </div>
</x-admin::layouts>

