@props(['id', 'title', 'formId' => null, 'submitText' => 'Save'])


<div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="{{ $id }}">

    <div class="offcanvas-header border-bottom">
        <h4>{{ $title }}</h4>
        <button class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="ti ti-x"></i>
        </button>
    </div>

    <div class="offcanvas-body d-flex flex-column">

        <div class="flex-grow-1">
            {{ $slot }}
        </div>

        <div class="d-flex justify-content-end gap-2 mt-3 border-top pt-3">
            <button class="btn btn-light" data-bs-dismiss="offcanvas">Cancel</button>

            <button type="submit" data-form="{{ $formId }}" form="{{ $formId }}" class="btn btn-primary js-submit-btn">
                {{ $submitText }}
            </button>
        </div>

    </div>
</div>
