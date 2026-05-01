<!-- gallery_preview field - uses Laravel FileManager for image selection -->
@php
    $current_value = old($field['name'], null) ?? $field['value'] ?? $field['default'] ?? [];
    if (is_string($current_value)) {
        $current_value = json_decode($current_value, true) ?? [];
    }
    if (!is_array($current_value)) {
        $current_value = [];
    }
@endphp

@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')

    <div id="gallery-field-wrapper">
        {{-- Hidden input to store JSON array --}}
        <input type="hidden" name="{{ $field['name'] }}" id="gallery-data" value="{{ json_encode($current_value) }}">

        {{-- Preview area --}}
        <div id="gallery-preview" style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:10px; min-height:50px; border:2px dashed #ddd; border-radius:8px; padding:10px;">
            @if(count($current_value) > 0)
                @foreach($current_value as $imagePath)
                    <div class="gallery-item" style="position:relative; width:130px; border:2px solid #e2e8f0; border-radius:8px; overflow:hidden;">
                        <img src="{{ asset('storage/' . $imagePath) }}" style="width:130px; height:95px; object-fit:cover; display:block;">
                        <button type="button" class="gallery-remove-btn" data-path="{{ $imagePath }}" style="position:absolute; top:3px; right:3px; background:#ef4444; color:#fff; border:none; border-radius:50%; width:24px; height:24px; font-size:14px; cursor:pointer; line-height:22px;">&times;</button>
                    </div>
                @endforeach
            @else
                <p id="gallery-empty-text" style="color:#aaa; margin:auto; font-size:13px;">Chưa có ảnh nào. Bấm nút bên dưới để thêm.</p>
            @endif
        </div>

        {{-- Buttons --}}
        <div style="display:flex; gap:8px;">
            <button type="button" id="gallery-lfm-btn" class="btn btn-outline-primary btn-sm">
                <i class="la la-image"></i> Chọn ảnh từ File Manager
            </button>
            <label class="btn btn-outline-secondary btn-sm mb-0" style="cursor:pointer;">
                <i class="la la-upload"></i> Upload từ máy
                <input type="file" id="gallery-upload-input" multiple accept="image/*" style="display:none;">
            </label>
        </div>
    </div>

    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')

@push('crud_fields_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var galleryData = JSON.parse(document.getElementById('gallery-data').value || '[]');

    function renderGallery() {
        var container = document.getElementById('gallery-preview');
        container.innerHTML = '';

        if (galleryData.length === 0) {
            container.innerHTML = '<p style="color:#aaa; margin:auto; font-size:13px;">Chưa có ảnh nào. Bấm nút bên dưới để thêm.</p>';
        }

        galleryData.forEach(function(path, index) {
            var src = path.startsWith('http') ? path : '{{ asset("storage") }}/' + path;
            var div = document.createElement('div');
            div.className = 'gallery-item';
            div.style.cssText = 'position:relative; width:130px; border:2px solid #e2e8f0; border-radius:8px; overflow:hidden;';
            div.innerHTML = '<img src="' + src + '" style="width:130px; height:95px; object-fit:cover; display:block;">' +
                '<button type="button" class="gallery-remove-btn" style="position:absolute; top:3px; right:3px; background:#ef4444; color:#fff; border:none; border-radius:50%; width:24px; height:24px; font-size:14px; cursor:pointer; line-height:22px;">&times;</button>';

            div.querySelector('.gallery-remove-btn').addEventListener('click', function() {
                galleryData.splice(index, 1);
                updateAndRender();
            });

            container.appendChild(div);
        });
    }

    function updateAndRender() {
        document.getElementById('gallery-data').value = JSON.stringify(galleryData);
        renderGallery();
    }

    // Remove button for initial items
    document.querySelectorAll('.gallery-remove-btn[data-path]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var path = this.getAttribute('data-path');
            galleryData = galleryData.filter(function(p) { return p !== path; });
            updateAndRender();
        });
    });

    // LFM button
    document.getElementById('gallery-lfm-btn').addEventListener('click', function() {
        var route = '{{ url("filemanager?type=Images") }}';
        window.open(route, 'FileManager', 'width=900,height=600');

        // LFM will call this function when files are selected
        window.SetUrl = function(items) {
            // items can be a string URL or an array
            if (typeof items === 'string') {
                items = [items];
            }
            if (!Array.isArray(items)) {
                items = [items];
            }
            items.forEach(function(item) {
                var url = typeof item === 'object' ? item.url : item;
                // Convert full URL to relative storage path
                var storagePath = url.replace(/.*\/storage\//, '');
                if (storagePath && galleryData.indexOf(storagePath) === -1) {
                    galleryData.push(storagePath);
                }
            });
            updateAndRender();
        };
    });

    // Upload from computer
    document.getElementById('gallery-upload-input').addEventListener('change', function() {
        var files = this.files;
        if (!files.length) return;

        var formData = new FormData();
        for (var i = 0; i < files.length; i++) {
            formData.append('file', files[i]);
        }

        // Upload each file via LFM
        Array.from(files).forEach(function(file) {
            var fd = new FormData();
            fd.append('file', file);
            fd.append('_token', '{{ csrf_token() }}');
            fd.append('working_dir', '/shares');

            fetch('{{ url("filemanager/upload") }}?type=Images', {
                method: 'POST',
                body: fd,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data && !data.error) {
                    // Refresh: get file list
                    var fileName = file.name;
                    var storagePath = 'photos/shares/' + fileName;
                    galleryData.push(storagePath);
                    updateAndRender();
                }
            })
            .catch(function(err) { console.error('Upload error:', err); });
        });

        this.value = '';
    });
});
</script>
@endpush
