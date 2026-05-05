@props([
    'id' => null,
    'label',
    'name',
    'options' => [],
    'required' => false,
    'value' => null,
    'multiple' => false,
    'disabledOptions' => [],
])

@php
    // 🔹 normalize name
    $baseName = str_replace('[]', '', $name);

    // 🔹 selected value
    $selectedValues = old($baseName, $value);

    // 🔥 handle boolean (IMPORTANT FIX)
    if ($selectedValues === false) {
        $selectedValues = '0';
    }

    if ($multiple) {
        $selectedValues = is_array($selectedValues) ? $selectedValues : [];
    }

    // 🔹 normalize disabled options
    $disabledOptions = collect($disabledOptions)->map(fn($item) => is_object($item) ? $item->value : $item)->toArray();
@endphp

<div class="mb-3">

    {{-- 🔹 Label --}}
    <label class="col-form-label">
        {{ $label }}
        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    {{-- 🔹 Select --}}
    <select id="{{ $id }}" name="{{ $name }}" class="form-select {{ $multiple ? 'select2' : '' }}"
        {{ $multiple ? 'multiple' : '' }}>

        {{-- 🔹 Placeholder --}}
        @unless ($multiple)
            <option value="" disabled {{ $selectedValues === null ? 'selected' : '' }}>
                Select
            </option>
        @endunless

        {{-- 🔹 Options --}}
        @foreach ($options as $key => $val)
            @php
                // 🔥 disable logic
                $isDisabled =
                    in_array($key, $disabledOptions) && ($multiple || (string) $selectedValues !== (string) $key);
            @endphp

            @if ($multiple)
                <option value="{{ $key }}"
                    {{ in_array((string) $key, array_map('strval', $selectedValues)) ? 'selected' : '' }}
                    {{ $isDisabled ? 'disabled' : '' }}>
                    {{ $val }}
                </option>
            @else
                <option value="{{ $key }}" {{ (string) $selectedValues === (string) $key ? 'selected' : '' }}
                    {{ $isDisabled ? 'disabled' : '' }}>
                    {{ $val }}
                </option>
            @endif
        @endforeach

    </select>

    {{-- 🔹 Laravel validation --}}
    @error($baseName)
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror

    {{-- 🔹 AJAX validation --}}
    <div class="invalid-feedback error-{{ $baseName }}"></div>

</div>
