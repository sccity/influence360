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
                <span class="font-semibold">Bill File ID:</span> 
                {{ $billFile->id }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">Year:</span> 
                {{ $billFile->year }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">Session:</span> 
                {{ $billFile->session }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">Name:</span> 
                {{ $billFile->name }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">Sponsor:</span> 
                {{ $billFile->sponsor }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">Created:</span> 
                {{ $billFile->created_at->format('Y/m/d') }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">Updated:</span> 
                {{ $billFile->updated_at->format('Y/m/d') }}
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">Is Tracked:</span> 
                {{ $billFile->is_tracked ? 'Yes' : 'No' }}
            </p>
        </div>
    </div>
</div>
