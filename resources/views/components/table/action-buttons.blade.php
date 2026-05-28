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
            <a href="#" class="dropdown-item delete-btn" data-url="{{ $deleteUrl }}">

                <i class="ti ti-trash text-danger"></i> Delete
            </a>
            {{-- @endcan
            @endif --}}

        </div>
    </div>
</td>
