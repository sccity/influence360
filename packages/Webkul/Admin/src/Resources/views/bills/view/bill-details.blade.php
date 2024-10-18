<div class="bg-white dark:bg-gray-900 rounded box-shadow">
    <div class="flex w-full flex-col gap-2 p-4">
        <p class="text-base text-gray-800 dark:text-white font-semibold">
            @lang('admin::app.bills.view.bill-details')
        </p>

        <div class="flex flex-col gap-1.5">
            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">@lang('admin::app.bills.view.bill_number'):</span> 
                {{ $bill->bill_number }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">@lang('admin::app.bills.view.short_title'):</span> 
                {{ $bill->short_title }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">@lang('admin::app.bills.view.session'):</span> 
                {{ $bill->session }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">@lang('admin::app.bills.view.bill_year'):</span> 
                {{ $bill->bill_year }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">@lang('admin::app.bills.view.sponsor'):</span> 
                {{ $bill->sponsor }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">@lang('admin::app.bills.view.floor_sponsor'):</span> 
                {{ $bill->floor_sponsor }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">@lang('admin::app.bills.view.last_action'):</span> 
                {{ $bill->last_action }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">@lang('admin::app.bills.view.last_action_date'):</span> 
                {{ $bill->last_action_date ? $bill->last_action_date->format('Y-m-d H:i:s') : '' }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">@lang('admin::app.bills.view.ai_impact_rating'):</span> 
                {{ $bill->ai_impact_rating }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">@lang('admin::app.bills.view.is_tracked'):</span> 
                {{ $bill->is_tracked ? __('admin::app.common.yes') : __('admin::app.common.no') }}
            </p>
        </div>
    </div>
</div>
