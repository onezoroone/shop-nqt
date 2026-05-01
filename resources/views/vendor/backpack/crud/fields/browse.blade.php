<!-- field for Laravel File Manager -->
@php
    $field['wrapper'] = $field['wrapper'] ?? $field['wrapperAttributes'] ?? [];
    $field['wrapper']['class'] = $field['wrapper']['class'] ?? 'form-group col-sm-12';
    $field['wrapper']['class'] = $field['wrapper']['class'].' browse-field-wrapper';
    
    $prefix = url(config('lfm.url_prefix', '/laravel-filemanager'));
    $value = old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '';
@endphp

@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')

    <div class="input-group">
        <input 
            type="text" 
            name="{{ $field['name'] }}" 
            id="{{ $field['name'] }}_input" 
            value="{{ $value }}" 
            class="form-control" 
            @include('crud::fields.inc.attributes')
        >
        <span class="input-group-btn input-group-append">
            <button id="{{ $field['name'] }}_lfm" data-input="{{ $field['name'] }}_input" data-preview="{{ $field['name'] }}_preview" class="btn btn-primary" type="button">
                <i class="la la-image"></i> Browse
            </button>
        </span>
    </div>

    <div id="{{ $field['name'] }}_preview" style="margin-top:15px;max-height:100px;">
        @if($value)
            <img src="{{ $value }}" style="height: 5rem;">
        @endif
    </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')

@push('crud_fields_scripts')
    @bassetBlock('unisharp/laravel-filemanager/public/js/stand-alone-button.js')
    <script>
        (function( $ ){
            $.fn.filemanager = function(type, options) {
                type = type || 'file';

                this.on('click', function(e) {
                var route_prefix = (options && options.prefix) ? options.prefix : '/filemanager';
                var target_input = $('#' + $(this).data('input'));
                var target_preview = $('#' + $(this).data('preview'));
                window.open(route_prefix + '?type=' + type, 'FileManager', 'width=900,height=600');
                window.SetUrl = function (items) {
                    var file_path = items.map(function (item) {
                        return item.url;
                    }).join(',');

                    target_input.val('').val(file_path).trigger('change');
                    target_preview.html('');

                    items.forEach(function (item) {
                        target_preview.append(
                            $('<img>').css('height', '5rem').css('margin-right', '10px').attr('src', item.thumb_url || item.url)
                        );
                    });

                    target_preview.trigger('change');
                };
                return false;
                });
            }
        })(jQuery);
    </script>
    @endBassetBlock

    <script>
        $('#{{ $field['name'] }}_lfm').filemanager('image', {prefix: '{{ $prefix }}'});
    </script>
@endpush
