@props([
    'viewUrl' => null,
    'viewPermission' => null,

    'editUrl' => null,
    'editType' => 'page', // or 'canvas'

    'editPermission' => null,
    'editData' => null,
    'canvasTarget' => '#offcanvas',
    'formId' => '#form',

    'deleteUrl' => null,
    'deleteType' => 'normal', // or 'canvas'
    'deleteId' => null,
    'deletePermission' => null,
])


<td class="text-end">
    <div class="dropdown table-action">
        <a href="#" class="action-icon" data-bs-toggle="dropdown">
            <i class="fa fa-ellipsis-v"></i>
        </a>

        <div class="dropdown-menu dropdown-menu-end">

            {{-- View --}}
            {{-- @if ($viewUrl && $viewPermission)
                @can($viewPermission) --}}
            <a class="dropdown-item" href="{{ $viewUrl }}">
                <i class="ti ti-eye text-success"></i> View
            </a>
            {{-- @endcan
            @endif --}}

            {{-- Edit --}}
            {{-- @if ($editUrl && $editPermission)
                @can($editPermission) --}}
            @if ($editUrl)

                {{-- OFFCANVAS --}}
                @if ($editType === 'canvas')
                    <a href="#" class="dropdown-item edit-form" data-bs-toggle="offcanvas"
                        data-bs-target="{{ $canvasTarget }}" data-type="edit" data-url="{{ $editUrl }}"
                        data-method="PUT" data-data='@json($editData)' data-form="{{ $formId }}">

                        <i class="ti ti-edit text-primary"></i>

                        Edit

                    </a>

                    {{-- NORMAL PAGE --}}
                @else
                    <a href="{{ $editUrl }}" class="dropdown-item">

                        <i class="ti ti-edit text-primary"></i>

                        Edit

                    </a>
                @endif

            @endif
            {{-- @endcan
            @endif --}}

            {{-- Delete --}}
            {{-- @if ($deleteUrl && $deletePermission)
                @can($deletePermission) --}}
            @if ($deleteUrl)
                @if ($deleteType === 'canvas')
                    <a href="#" class="dropdown-item delete-btn" data-bs-toggle="offcanvas"
                        data-bs-target="{{ $canvasTarget }}" data-type="delete" data-url="{{ $deleteUrl }}"
                        data-method="DELETE" data-form="{{ $formId }}">

                        <i class="ti ti-trash text-danger"></i> Delete

                    </a>
                @else
                    <a href="#" class="dropdown-item" onclick="confirmDelete(event, {{ $deleteId }})">
                        <i class="ti ti-trash text-danger"></i> Delete
                    </a>

                    <form id="delete-form-{{ $deleteId }}" action="{{ $deleteUrl }}" method="POST"
                        class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            @endif
            {{-- @endcan
            @endif --}}

        </div>
    </div>
</td>
@push('scripts')
    <script>
        function confirmDelete(event, id) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete this item?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endpush
