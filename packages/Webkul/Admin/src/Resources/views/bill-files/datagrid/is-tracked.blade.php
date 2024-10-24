<span class="checkbox">
    <input type="checkbox" id="is_tracked_{{ $row->id }}" name="is_tracked" {{ $row->is_tracked ? 'checked' : '' }} onchange="toggleTracked({{ $row->id }})">
    <label for="is_tracked_{{ $row->id }}" class="checkbox-view"></label>
</span>

<script>
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
