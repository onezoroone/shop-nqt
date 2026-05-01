{{-- select2_multiple - wraps the existing select2 field with multiple enabled --}}
@php
    $field['multiple'] = true;
@endphp

@include('crud::fields.select2', ['field' => $field])
