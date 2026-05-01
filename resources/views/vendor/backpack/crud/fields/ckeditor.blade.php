{{-- CKEditor 5 (Free, no API key needed) --}}
@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')

    {{-- Hidden input to sync data --}}
    <input type="hidden" name="{{ $field['name'] }}" value="{{ old_empty_or_null($field['name'], '') ?? $field['value'] ?? $field['default'] ?? '' }}">

    <div
        id="ckeditor-{{ $field['name'] }}"
        class="ckeditor-instance"
        data-field-name="{{ $field['name'] }}"
        style="min-height: 300px;"
    >{!! old_empty_or_null($field['name'], '') ?? $field['value'] ?? $field['default'] ?? '' !!}</div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')

@push('crud_fields_styles')
    <style>
        .ck-editor__editable {
            min-height: 300px;
        }
    </style>
@endpush

@push('crud_fields_scripts')
    @loadOnce('ckeditor_scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.ckeditor-instance').forEach(function(el) {
                if (el.ckeditorInstance) return;

                ClassicEditor
                    .create(el, {
                        toolbar: {
                            items: [
                                'heading', '|',
                                'bold', 'italic', 'underline', 'strikethrough', '|',
                                'link', 'blockQuote', 'insertTable', '|',
                                'bulletedList', 'numberedList', 'outdent', 'indent', '|',
                                'imageUpload', 'mediaEmbed', '|',
                                'undo', 'redo', '|',
                                'sourceEditing'
                            ]
                        },
                        language: 'vi',
                        image: {
                            toolbar: ['imageTextAlternative', 'imageStyle:inline', 'imageStyle:block', 'imageStyle:side']
                        },
                        table: {
                            contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
                        }
                    })
                    .then(function(editor) {
                        el.ckeditorInstance = editor;
                        var fieldName = el.dataset.fieldName;
                        var hiddenInput = el.closest('.form-group').querySelector('input[name="' + fieldName + '"]');

                        editor.model.document.on('change:data', function() {
                            hiddenInput.value = editor.getData();
                        });

                        // Sync before form submit
                        el.closest('form').addEventListener('submit', function() {
                            hiddenInput.value = editor.getData();
                        });
                    })
                    .catch(function(error) {
                        console.error('CKEditor init error:', error);
                    });
            });
        });
    </script>
    @endLoadOnce
@endpush
