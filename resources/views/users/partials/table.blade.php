@forelse ($users as $user)
    <tr>

        <td>
            {{ $loop->iteration }}
        </td>

        <td>
            {{ $user->name }}
        </td>

        <td class="text-capitalize">
            {{ str_replace('_', ' ', $user->roles->first()?->name) }}
        </td>

        <td>
            {{ $user->email }}
        </td>

        <td>
            {{ $user->created_at->format('d M Y') }}
        </td>

        <td>
            {!! user_status_badge($user->status) !!}
        </td>

        <td class="text-end">

            <div class="dropdown table-action">

                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="dropdown" data-bs-display="static">

                    <i class="fa fa-ellipsis-v"></i>
                </a>
                @if (userCan('users.edit') || userCan('users.delete'))
                    <div class="dropdown-menu dropdown-menu-end">
                        @if (userCan('users.edit'))
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center edit-btn"
                                data-id="{{ $user->id }}" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvas_edit">

                                <i class="ti ti-edit text-primary me-2"></i>
                                Edit
                            </a>
                        @endif
                        @if (userCan('users.edit'))
                            <a href="{{ route('users.permissions', $user->id) }}"
                                class="dropdown-item d-flex align-items-center">
                                <i class="ti ti-shield text-success"></i>
                                Permission
                            </a>
                        @endif
                        @if (userCan('users.delete'))
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center delete-btn"
                                data-id="{{ $user->id }}" data-bs-toggle="modal" data-bs-target="#delete_contact">

                                <i class="ti ti-trash text-danger me-2"></i>
                                Delete
                            </a>
                        @endif
                @endif
            </div>

            </div>

        </td>

    </tr>

@empty

    <tr>
        <td colspan="6" class="text-center py-4">
            No users found
        </td>
    </tr>
@endforelse
