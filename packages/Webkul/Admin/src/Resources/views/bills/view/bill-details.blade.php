<div class="bg-white dark:bg-gray-900 rounded box-shadow">
    <div class="flex w-full flex-col gap-2 p-4">
        <p class="text-base text-gray-800 dark:text-white font-semibold">
            @lang('admin::app.bills.view.bill-details')
        </p>

        <div class="flex flex-col gap-1.5">
            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">@lang('admin::app.bills.view.billid'):</span> 
                {{ $bill->billid }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">@lang('admin::app.bills.view.name'):</span> 
                {{ $bill->name }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">@lang('admin::app.bills.view.status'):</span> 
                {{ $bill->status }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">@lang('admin::app.bills.view.session'):</span> 
                {{ $bill->session }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">@lang('admin::app.bills.view.year'):</span> 
                {{ $bill->year }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">@lang('admin::app.bills.view.is_tracked'):</span> 
                {{ $bill->is_tracked ? __('admin::app.common.yes') : __('admin::app.common.no') }}
            </p>
        </div>
    </div>
</div>