<!-- wysiwyg field - CKEditor 4 with Laravel FileManager -->
@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')

    <textarea
        name="{{ $field['name'] }}"
        data-init-function="bpFieldInitWysiwygElement"
        @include('crud::fields.inc.attributes', ['default_class' =>  'form-control'])
    >{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}</textarea>

    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')

@if ($crud->fieldTypeNotLoaded($field))
    @php
        $crud->markFieldTypeAsLoaded($field);
    @endphp

    @push('crud_fields_scripts')
        <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
        <script>
            function bpFieldInitWysiwygElement(element) {
                if (element.dataset.ckeditorInitialized) return;

                CKEDITOR.replace(element, {
                    height: 350,
                    allowedContent: true,
                    filebrowserImageBrowseUrl: '{{ url("filemanager?type=Images") }}',
                    filebrowserImageUploadUrl: '{{ url("filemanager/upload?type=Images&_token=".csrf_token()) }}',
                    filebrowserBrowseUrl: '{{ url("filemanager?type=Files") }}',
                    filebrowserUploadUrl: '{{ url("filemanager/upload?type=Files&_token=".csrf_token()) }}',
                    toolbar: [
                        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat'] },
                        { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'] },
                        { name: 'links', items: ['Link', 'Unlink'] },
                        { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar'] },
                        { name: 'styles', items: ['Format', 'Styles'] },
                        { name: 'tools', items: ['Maximize', 'Source'] }
                    ],
                    on: {
                        change: function() {
                            this.updateElement();
                        }
                    }
                });

                element.dataset.ckeditorInitialized = 'true';
            }
        </script>
    @endpush
@endif
