<div class="bg-white dark:bg-gray-900 rounded-lg box-shadow">
    <div class="p-4 flex justify-between items-center">
        <p class="text-lg font-bold text-gray-800 dark:text-white">
            @lang('admin::app.dashboard.index.initiative-overview')
        </p>
    </div>

    <div class="border-t dark:border-gray-800">
        <div class="p-2 flex items-center justify-between border-b dark:border-gray-800 text-xs">
            <span class="text-gray-500 dark:text-gray-400">Total Initiatives</span>
            <span class="font-medium text-gray-800 dark:text-white">{{ $totalInitiatives }}</span>
        </div>
        <div class="p-2 flex items-center justify-between border-b dark:border-gray-800 text-xs">
            <span class="text-gray-500 dark:text-gray-400">Open Initiatives</span>
            <span class="font-medium text-gray-800 dark:text-white">{{ $openInitiatives }} ({{ $openInitiativesPercentage }}%)</span>
        </div>
        <div class="p-2 flex items-center justify-between border-b dark:border-gray-800 text-xs">
            <span class="text-gray-500 dark:text-gray-400">Top Stage</span>
            <span class="font-medium text-gray-800 dark:text-white">{{ $topStage->name }} ({{ $topStagePercentage }}%)</span>
        </div>
        <div class="p-2 flex items-center justify-between text-xs">
            <span class="text-gray-500 dark:text-gray-400">Avg. Time to Close</span>
            <span class="font-medium text-gray-800 dark:text-white">{{ $avgTimeToClose }} days</span>
        </div>
    </div>
</div>
