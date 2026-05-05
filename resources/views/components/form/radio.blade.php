@props(['label', 'name', 'options' => [], 'required' => false, 'value' => null])

<div class="mb-3">
    <label class="col-form-label">
        {{ $label }}
        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    <div class="d-flex flex-wrap gap-3 mt-2">

        @foreach ($options as $key => $val)
            <label class="smtp-radio-card">

                <input type="radio"
                    name="{{ $name }}"
                    value="{{ $key }}"
                    {{ old($name, $value) == $key ? 'checked' : '' }}>

                <div>
                    <strong>{{ $val }}</strong>
                </div>

            </label>
        @endforeach

    </div>

    <div class="invalid-feedback error-{{ $name }}"></div>
</div>
