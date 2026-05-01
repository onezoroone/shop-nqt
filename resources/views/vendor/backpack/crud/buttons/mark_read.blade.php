@if (!$entry->is_read)
    <a href="javascript:void(0)" onclick="markAsRead(this)" data-route="{{ backpack_url('contact/' . $entry->getKey() . '/mark-read') }}" class="btn btn-sm btn-link" data-button-type="mark_read">
        <i class="la la-eye"></i> Mark Read
    </a>
@else
    <span class="btn btn-sm btn-link text-muted"><i class="la la-check-circle"></i> Read</span>
@endif

@once
@push('after_scripts')
<script>
    if (typeof markAsRead !== 'function') {
        function markAsRead(button) {
            var route = button.getAttribute('data-route');
            fetch(route, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            }).then(function(response) {
                return response.json();
            }).then(function() {
                button.outerHTML = '<span class="btn btn-sm btn-link text-muted"><i class="la la-check-circle"></i> Read</span>';
                if (typeof Noty !== 'undefined') {
                    new Noty({ type: "success", text: "Marked as read!" }).show();
                }
            });
        }
    }
</script>
@endpush
@endonce
