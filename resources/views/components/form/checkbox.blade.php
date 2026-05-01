@props(['label', 'name', 'value' => 1, 'checked' => false])

@php
    $isChecked = old($name, $checked) ? true : false;
@endphp

<div class="form-check mb-3">

    <input type="checkbox" name="{{ $name }}" value="{{ $value }}" id="{{ $name }}"
        {{ $isChecked ? 'checked' : '' }}
        {{ $attributes->merge([
            'class' => 'form-check-input ' . ($errors->has($name) ? 'is-invalid' : ''),
        ]) }}>

    <label class="form-check-label" for="{{ $name }}">
        {{ $label }}
    </label>

    {{-- Laravel validation --}}
    @error($name)
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror

    {{-- AJAX validation --}}
    <div class="invalid-feedback error-{{ $name }}"></div>
</div>
