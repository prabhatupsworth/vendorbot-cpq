@props(['label', 'name', 'options' => [], 'required' => false, 'value' => null])

<div class="mb-3">
    <label class="col-form-label">
        {{ $label }} @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    <select name="{{ $name }}" class="form-select">
        <option value="">Select</option>

        @foreach ($options as $key => $val)
            <option value="{{ $key }}" {{ old($name, $value) == $key ? 'selected' : '' }}>
                {{ $val }}
            </option>
        @endforeach
    </select>

    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

    <div class="invalid-feedback error-{{ $name }}"></div>
</div>
