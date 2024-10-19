<div class="bg-white dark:bg-gray-900 rounded-lg box-shadow p-4">
    <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Key Metrics</h2>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <p class="text-sm text-gray-600 dark:text-gray-400">Total Initiatives</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalInitiatives }}</p>
            <p class="text-xs text-green-500">↑ {{ $initiativesGrowth }}%</p>
        </div>
        <div>
            <p class="text-sm text-gray-600 dark:text-gray-400">Average Initiative Value</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white">${{ number_format($averageInitiativeValue, 2) }}</p>
            <p class="text-xs text-green-500">↑ {{ $valueGrowth }}%</p>
        </div>
        <div>
            <p class="text-sm text-gray-600 dark:text-gray-400">Total Persons</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalPersons }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600 dark:text-gray-400">Total Organizations</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalOrganizations }}</p>
        </div>
    </div>
</div>