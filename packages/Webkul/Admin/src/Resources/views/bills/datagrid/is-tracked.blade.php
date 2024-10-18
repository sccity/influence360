<span class="checkbox">
    <input type="checkbox" id="is_tracked_{{ $row->id }}" name="is_tracked" {{ $row->is_tracked ? 'checked' : '' }} onchange="toggleTracked({{ $row->id }})">
    <label for="is_tracked_{{ $row->id }}" class="checkbox-view"></label>
</span>

<script>
function toggleTracked(id) {
    fetch("{{ route('admin.bill-files.toggle-tracked', ':id') }}".replace(':id', id), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ is_tracked: document.getElementById('is_tracked_' + id).checked })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Optionally, you can show a success message here
        } else {
            // Handle error, maybe revert the checkbox state
            document.getElementById('is_tracked_' + id).checked = !document.getElementById('is_tracked_' + id).checked;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Revert the checkbox state on error
        document.getElementById('is_tracked_' + id).checked = !document.getElementById('is_tracked_' + id).checked;
    });
}
</script>

