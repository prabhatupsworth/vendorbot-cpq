@props(['label', 'name', 'required' => false])

<div class="mb-3">
    <label class="col-form-label">
        {{ $label }}
        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    <input type="file" name="{{ $name }}" class="form-control">

    {{-- Preview --}}
    <img id="preview-{{ $name }}" class="mt-2 d-none" width="120" />

    {{-- Validation --}}
    <div class="invalid-feedback error-{{ $name }}"></div>
</div>

<script>
    document.addEventListener('change', function(e) {

        const input = e.target;

        if (input.type !== 'file') return;

        const file = input.files[0];
        if (!file) return;

        const preview = document.getElementById(`preview-${input.name}`);

        if (preview) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('d-none');
        }
    });
</script>
