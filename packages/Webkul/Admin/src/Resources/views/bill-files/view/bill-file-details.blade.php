<div class="flex items-center justify-between">
    <h2 class="text-xl font-bold">
        @lang('admin::app.bill-files.view.title', ['name' => $billFile->name])
    </h2>

    <p class="text-gray-600 dark:text-gray-300">
        <span class="font-semibold">@lang('admin::app.bill-files.view.sponsor'):</span> 
        {{ $billFile->sponsor }}
    </p>
</div>

<div class="bg-white dark:bg-gray-900 rounded box-shadow mt-4">
    <div class="flex flex-col gap-2 p-4">
        <p class="text-base text-gray-800 dark:text-white font-semibold">
            @lang('admin::app.bill-files.view.details')
        </p>

        <div class="flex flex-col gap-1.5">
            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">@lang('admin::app.bill-files.view.billid'):</span> 
                {{ $billFile->billid }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">Sponsor:</span> 
                {{ $billFile->sponsor }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">@lang('admin::app.bill-files.view.status'):</span> 
                {{ $billFile->status }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">@lang('admin::app.bill-files.view.session'):</span> 
                {{ $billFile->session }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">@lang('admin::app.bill-files.view.year'):</span> 
                {{ $billFile->year }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">@lang('admin::app.bill-files.view.is_tracked'):</span> 
                {{ $billFile->is_tracked ? __('admin::app.common.yes') : __('admin::app.common.no') }}
            </p>
        </div>
    </div>
</div>
