<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.bills.tracked.title')
    </x-slot>

    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold">
            @lang('admin::app.bills.tracked.title')
        </h1>
    </div>

    <div class="mt-3">
        <x-admin::datagrid src="{{ route('admin.bills.index', ['is_tracked' => 1]) }}" />
    </div>
</x-admin::layouts>