@props(['label', 'name', 'required' => false, 'value' => null])

<div class="mb-3">
    <label class="col-form-label">
        {{ $label }} @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    <input name="{{ $name }}" value="{{ old($name, $value) }}"
        {{ $attributes->merge(['class' => 'form-control']) }}>
    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
    <div class="invalid-feedback error-{{ $name }}"></div>
</div>
