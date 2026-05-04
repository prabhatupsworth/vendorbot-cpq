@props(['label', 'name', 'options' => [], 'required' => false, 'value' => null, 'multiple' => false])
@php
    $baseName = str_replace('[]', '', $name);
    $selectedValues = old($baseName, $value);

    if ($multiple) {
        $selectedValues = is_array($selectedValues) ? $selectedValues : [];
    }
@endphp

<div class="mb-3">
    <label class="col-form-label">
        {{ $label }}
        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    <select name="{{ $name }}" class="form-select select2" {{ $multiple ? 'multiple' : '' }}>

        @unless ($multiple)
            <option value="">Select</option>
        @endunless

        @foreach ($options as $key => $val)
            @if ($multiple)
                <option value="{{ $key }}" {{ in_array($key, $selectedValues) ? 'selected' : '' }}>
                    {{ $val }}
                </option>
            @else
                <option value="{{ $key }}" {{ $selectedValues == $key ? 'selected' : '' }}>
                    {{ $val }}
                </option>
            @endif
        @endforeach

    </select>

    @error($baseName)
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror

    <div class="invalid-feedback error-{{ $baseName }}"></div>
</div>
