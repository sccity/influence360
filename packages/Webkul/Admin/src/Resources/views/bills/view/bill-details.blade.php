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
                <span class="impact-rating px-2 py-1 rounded text-white" data-rating="{{ $bill->ai_impact_rating }}">{{ $bill->ai_impact_rating }}
                </span>
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">@lang('admin::app.bills.view.is_tracked'):</span>&nbsp;&nbsp;
                <span class="checkbox">
                    <input type="checkbox" id="is_tracked_{{ $bill->id }}" name="is_tracked" {{ $bill->is_tracked ? 'checked' : '' }} onchange="toggleTracked({{ $bill->id }})">
                    <label for="is_tracked_{{ $bill->id }}" class="checkbox-view"></label>
                </span>
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const impactRating = document.querySelector('.impact-rating');

        if (impactRating) {
            const rating = parseInt(impactRating.dataset.rating);
            impactRating.style.backgroundColor = getColorForRating(rating);
        }
    });

    function getColorForRating(rating) {
        if (rating === 0) return '#000000'; // Black for 0
        if (rating <= 3) return '#006400'; // Dark Green for 1-3
        if (rating <= 6) return '#FFA500'; // Orange for 4-6
        if (rating <= 9) return '#FF4500'; // Red-Orange for 7-9
        return '#FF0000'; // Bright Red for 10
    }

    function toggleTracked(id) {
        var checkbox = document.getElementById('is_tracked_' + id);
        var isChecked = checkbox.checked;

        fetch("{{ route('admin.bills.toggle-tracked', ':id') }}".replace(':id', id), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ is_tracked: isChecked })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.addFlash({
                    type: 'success',
                    message: data.message
                });
            } else {
                window.eventBus.emit('add-flash', {
                    type: 'error',
                    message: "@lang('admin::app.bills.notifications.error')"
                });
                checkbox.checked = !isChecked;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            window.eventBus.emit('add-flash', {
                type: 'error',
                message: "@lang('admin::app.bills.notifications.error')"
            });
            checkbox.checked = !isChecked;
        });
    }
</script>
@endpush
