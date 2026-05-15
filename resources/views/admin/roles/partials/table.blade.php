@forelse ($roles as $role)
    <tr>

        <td>
            {{ $loop->iteration }}
        </td>

        <td>
            <div class="d-flex align-items-center">

                <div class="avatar avatar-sm bg-primary rounded-circle me-2">
                    <span class="text-white fw-bold">
                        {{ strtoupper(substr($role->name, 0, 1)) }}
                    </span>
                </div>

                <div>
                    <h6 class="mb-0 fs-14">
                        {{ ucwords(str_replace('_', ' ', $role->name)) }}
                    </h6>
                </div>

            </div>
        </td>

        <td>
            {{ $role->created_at->format('d M Y') }}
        </td>

        <td class="text-end">

            <div class="dropdown table-action">

                <a href="#" class="action-icon" data-bs-toggle="dropdown">

                    <i class="fa fa-ellipsis-v"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-end">

                    <a href="javascript:void(0);" class="dropdown-item edit-role" data-id="{{ $role->id }}"
                        data-name="{{ $role->name }}">

                        <i class="ti ti-edit text-blue"></i>
                        Edit
                    </a>

                    <a class="dropdown-item" href="{{ url('/roles/' . $role->id . '/permissions') }}">

                        <i class="ti ti-shield text-success"></i>
                        Permission
                    </a>

                    <!-- DELETE BUTTON -->
                    <a href="javascript:void(0);" class="dropdown-item delete-role" data-id="{{ $role->id }}"
                        data-name="{{ $role->name }}">

                        <i class="ti ti-trash text-danger"></i>
                        Delete
                    </a>

                </div>

            </div>

        </td>

    </tr>

@empty

    <tr>
        <td colspan="4" class="text-center py-4">
            No roles found
        </td>
    </tr>
@endforelse
