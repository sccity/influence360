<!-- packages/Webkul/Admin/src/Resources/views/bill-files/view/all.blade.php -->

<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.bill-files.view.title', ['name' => $billFile->name])
    </x-slot>

    <!-- Content -->
    <div class="flex gap-4">
        <!-- Left Panel -->
        {!! view_render_event('admin.bill-files.view.left.before', ['billFile' => $billFile]) !!}

        <div class="flex-1">
            <div class="flex flex-col gap-2 mb-4">
                <p class="text-xl text-gray-800 dark:text-white font-bold">
                    {{ $billFile->name }}
                </p>

                <p class="text-gray-600 dark:text-gray-300">
                    {{ $billFile->billid }}
                </p>
            </div>

            <!-- Bill File Information -->
            <div class="bg-white dark:bg-gray-900 rounded box-shadow">
                @include('admin::bill-files.view.bill-file-details')
            </div>
        </div>

        {!! view_render_event('admin.bill-files.view.left.after', ['billFile' => $billFile]) !!}

        <!-- Right Panel -->
        <div class="w-[400px]">
            {!! view_render_event('admin.bill-files.view.right.before', ['billFile' => $billFile]) !!}

            <!-- Activities -->
            <x-admin::accordion>
                <x-slot:header>
                    <p class="p-2.5 text-gray-800 dark:text-white text-base font-semibold">
                        @lang('admin::app.bill-files.view.activities')
                    </p>
                </x-slot>

                <x-slot:content>
                    @include('admin::bill-files.view.activities')
                </x-slot>
            </x-admin::accordion>

            {!! view_render_event('admin.bill-files.view.right.after', ['billFile' => $billFile]) !!}
        </div>
    </div>
</x-admin::layouts>
