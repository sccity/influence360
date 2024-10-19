<div class="bg-white dark:bg-gray-900 rounded-lg box-shadow">
    <div class="p-4 flex justify-between items-center">
        <p class="text-lg font-bold text-gray-800 dark:text-white">
            @lang('admin::app.dashboard.index.tracked-bills')
        </p>
        <a href="{{ route('admin.bills.tracked') }}" class="text-sm text-blue-600 hover:underline">
            @lang('admin::app.dashboard.index.view-all')
        </a>
    </div>

    <div class="border-t dark:border-gray-800">
        <x-admin::datagrid src="{{ route('admin.dashboard.tracked-bills') }}" />
    </div>
</div>
