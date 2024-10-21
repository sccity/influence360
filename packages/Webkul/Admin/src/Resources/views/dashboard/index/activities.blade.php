<div class="mb-4">
    <div class="p-4 flex justify-between items-center">
        <p class="text-lg font-bold text-gray-800 dark:text-white">
            @lang('admin::app.dashboard.index.all-activities')
        </p>
        <a href="{{ route('admin.activities.index') }}" class="text-sm text-blue-600 hover:underline">
            @lang('admin::app.dashboard.index.view-all')
        </a>
    </div>

    <div class="border-t dark:border-gray-800">
        <table class="w-full">
            <tbody>
                @forelse ($recentActivities->take(10) as $activity)
                    <tr class="border-b last:border-b-0 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="py-2 px-4 w-12">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full text-lg
                                @if ($activity->type == 'call') bg-blue-100 text-blue-600
                                @elseif ($activity->type == 'meeting') bg-purple-100 text-purple-600
                                @elseif ($activity->type == 'lunch') bg-yellow-100 text-yellow-600
                                @elseif ($activity->type == 'email') bg-green-100 text-green-600
                                @else bg-gray-100 text-gray-600 @endif">
                                @if ($activity->type == 'call')
                                    <i class="icon-call"></i>
                                @elseif ($activity->type == 'meeting')
                                    <i class="icon-meeting"></i>
                                @elseif ($activity->type == 'lunch')
                                    <i class="icon-lunch"></i>
                                @elseif ($activity->type == 'email')
                                    <i class="icon-email"></i>
                                @else
                                    <i class="icon-note"></i>
                                @endif
                            </div>
                        </td>
                        <td class="py-2 px-4 w-2/5">
                            <p class="text-sm font-medium text-gray-800 dark:text-white">
                                @if ($activity->type == 'note' && !$activity->title)
                                    {{ Str::limit($activity->comment, 70) }}
                                @else
                                    {{ $activity->title }}
                                @endif
                            </p>
                        </td>
                        <td class="py-2 px-4 w-1/6">
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ ucfirst($activity->type) }}</p>
                        </td>
                        <td class="py-2 px-4 w-1/6">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $activity->user ? $activity->user->name : 'System' }}
                            </p>
                        </td>
                        <td class="py-2 px-4 text-right w-1/6">
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $activity->created_at->diffForHumans() }}</p>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500 dark:text-gray-400">
                            @lang('admin::app.dashboard.index.no-activities')
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
