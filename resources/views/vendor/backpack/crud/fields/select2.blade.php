{{-- Select2 (đơn hoặc nhiều) - Select2 với tìm kiếm, dùng 'multiple' => true để chọn nhiều --}}
@php
    $field['multiple'] = $field['multiple'] ?? false;

    if (!isset($field['options'])) {
        $options = $field['model']::all();
    } else {
        $options = call_user_func($field['options'], $field['model']::query());
    }

    if ($field['multiple']) {
        $field['allows_null'] = $field['allows_null'] ?? true;
        $current_value = old_empty_or_null($field['name'], collect()) ?? $field['value'] ?? $field['default'] ?? collect();
        if (is_a($current_value, \Illuminate\Support\Collection::class)) {
            $current_value = $current_value->pluck(app($field['model'])->getKeyName())->toArray();
        }
        if (!is_array($current_value)) {
            $current_value = $current_value ? (array) $current_value : [];
        }
    } else {
        $current_value = old_empty_or_null($field['name'], '') ?? $field['value'] ?? $field['default'] ?? '';
        $entity_model = $crud->getRelationModel($field['entity'], -1);
        $field['allows_null'] = $field['allows_null'] ?? $entity_model::isColumnNullable($field['name']);
        if (is_object($current_value) && is_subclass_of(get_class($current_value), 'Illuminate\Database\Eloquent\Model')) {
            $current_value = $current_value->getKey();
        }
    }
@endphp

@include('crud::fields.inc.wrapper_start')

<label class="form-label">{!! $field['label'] !!}</label>
@include('crud::fields.inc.translatable_icon')

@if($field['multiple'])
    {{-- Gửi value rỗng khi không chọn gì --}}
    <input type="hidden" name="{{ $field['name'] }}" value="" @if(in_array('disabled', $field['attributes'] ?? [])) disabled @endif />
@endif

@if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
    @if(isset($field['prefix'])) <span class="input-group-text">{!! $field['prefix'] !!}</span> @endif
    <select
        name="{{ $field['multiple'] ? $field['name'].'[]' : $field['name'] }}"
        class="form-control select2-field"
        data-placeholder="{{ $field['placeholder'] ?? ($field['label'] ?? '') }}"
        data-allow-clear="{{ (isset($field['allows_null']) && $field['allows_null']) ? '1' : '0' }}"
        data-multiple="{{ $field['multiple'] ? '1' : '0' }}"
        @if($field['multiple']) multiple @endif
        style="width: 100%;"
        @include('crud::fields.inc.attributes', ['default_class' => ''])
        >
        @if (!$field['multiple'] && $field['allows_null'])
            <option value="">-</option>
        @endif

        @if (count($options))
            @foreach ($options as $connected_entity_entry)
                @php
                    $key = $connected_entity_entry->getKey();
                    $selected = $field['multiple'] ? in_array($key, $current_value) : ($current_value == $key);
                @endphp
                @if($selected)
                    <option value="{{ $key }}" selected>{{ $connected_entity_entry->{$field['attribute']} }}</option>
                @else
                    <option value="{{ $key }}">{{ $connected_entity_entry->{$field['attribute']} }}</option>
                @endif
            @endforeach
        @endif
    </select>
    @if(isset($field['suffix'])) <span class="input-group-text">{!! $field['suffix'] !!}</span> @endif
@if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif

{{-- Gợi ý --}}
@if (isset($field['hint']))
    <p class="help-block">{!! $field['hint'] !!}</p>
@endif

@include('crud::fields.inc.wrapper_end')

@if ($crud->fieldTypeNotLoaded($field))
    @php
        $crud->markFieldTypeAsLoaded($field);
    @endphp

    @push('crud_fields_styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
        <style>
            .select2-container { width: 100% !important; }
            .select2-container--bootstrap-5 .select2-selection { min-height: 38px; border-color: #ced4da; }
            .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice { background-color: #6366f1; color: #fff; border: none; border-radius: 4px; }
            .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove { color: #fff; }
        </style>
    @endpush

    @push('crud_fields_scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(function() {
                $('.select2-field').each(function() {
                    var $el = $(this);
                    $el.select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        allowClear: $el.data('allow-clear') == 1,
                        placeholder: $el.data('placeholder') || '',
                        multiple: $el.data('multiple') == 1
                    });
                });
            });
        </script>
    @endpush
@endif
