@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="content">

            <div class="row">
                <div class="col-md-12">

                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="page-title">User<span class="count-title">List</span></h4>
                            </div>
                            <div class="col-4 text-end">
                                <div class="head-icons">
                                    <a href="{{route('users.index')}}" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-original-title="Refresh"><i class="ti ti-refresh-dot"></i></a>
                                    <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-original-title="Collapse" id="collapse-header"><i
                                            class="ti ti-chevrons-up"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->

                    <div class="card">

                        <div class="card-header">
                            <!-- Search -->
                            <div class="row align-items-center">
                                <div class="col-sm-4">
                                    <div class="icon-form mb-3 mb-sm-0">
                                        <span class="form-icon"><i class="ti ti-search"></i></span>
                                        <input id="userSearch" type="text" class="form-control" placeholder="Search User">
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="d-flex align-items-center flex-wrap row-gap-2 justify-content-sm-end">
                                        <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="offcanvas"
                                            data-bs-target="#offcanvas_add"><i
                                                class="ti ti-square-rounded-plus me-2"></i>Add
                                            user</a>
                                    </div>
                                </div>
                            </div>
                            <!-- /Search -->
                        </div>

                        <div class="card-body">

                            <!-- Manage Users List -->
                            <div class="table-responsive custom-table">
                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Created</th>
                                            <th>Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                                <td>
                                                    {!! user_status_badge($user->status) !!}
                                                </td>
                                                <td class="text-end">
                                                    <div class="dropdown table-action">

                                                        <!-- TOGGLE -->
                                                        <a href="javascript:void(0);" class="action-icon"
                                                            data-bs-toggle="dropdown" data-bs-display="static">
                                                            <i class="fa fa-ellipsis-v"></i>
                                                        </a>

                                                        <!-- MENU -->
                                                        <div class="dropdown-menu dropdown-menu-end">

                                                            <!-- EDIT -->
                                                            @can('users.edit')
                                                                <a href="javascript:void(0);"
                                                                    class="dropdown-item d-flex align-items-center edit-btn"
                                                                    data-id="{{ $user->id }}" data-bs-toggle="offcanvas"
                                                                    data-bs-target="#offcanvas_edit">
                                                                    <i class="ti ti-edit text-primary me-2"></i> Edit
                                                                </a>
                                                            @endcan

                                                            @can('users.delete')
                                                                <!-- DELETE -->
                                                                <a href="javascript:void(0);"
                                                                    class="dropdown-item d-flex align-items-center delete-btn"
                                                                    data-id="{{ $user->id }}" data-bs-toggle="modal"
                                                                    data-bs-target="#delete_contact">
                                                                    <i class="ti ti-trash text-danger me-2"></i> Delete
                                                                </a>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="datatable-length"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="datatable-paginate"></div>
                                </div>
                            </div>
                            <!-- /Manage Users List -->

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <!-- Add User -->
    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_add">
        <div class="offcanvas-header border-bottom">
            <h5 class="fw-semibold">Add New User</h5>
            <button type="button"
                class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="ti ti-x"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-12">
                        <x-image-upload name="profile_image" />
                    </div>
                    <!-- NAME -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="col-form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>

                    <!-- EMAIL -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="col-form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>

                    <!-- PASSWORD -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="col-form-label">Password <span class="text-danger">*</span></label>
                            <div class="icon-form-end">
                                <span class="form-icon"><i class="ti ti-eye-off"></i></span>
                                <input name="password" type="password" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <!-- ROLE (🔥 Dynamic) -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="col-form-label">Role <span class="text-danger">*</span></label>
                            <select name="role" class="select" required>
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- STATUS -->
                    <div class="col-md-6">
                        <div class="radio-wrap">
                            <label class="col-form-label">Status</label>
                            <div class="d-flex align-items-center">

                                <div class="me-2">
                                    <input type="radio" class="status-radio" id="active1" name="status"
                                        value="1" checked>
                                    <label for="active1">Active</label>
                                </div>

                                <div>
                                    <input type="radio" class="status-radio" id="inactive1" name="status"
                                        value="0">
                                    <label for="inactive1">Inactive</label>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary">Create User</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /Add User -->

    <!-- Edit User -->
    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_edit">
        <div class="offcanvas-header border-bottom">
            <h5 class="fw-semibold">Edit User</h5>
            <button type="button"
                class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="ti ti-x"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <form id="editUserForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12">
                        <x-image-upload name="profile_image" id="edit_profile_image" />
                    </div>
                    <!-- NAME -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="col-form-label">Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control">
                        </div>
                    </div>

                    <!-- EMAIL -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="col-form-label">Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control">
                        </div>
                    </div>
                    <!-- ROLE -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="col-form-label">Role</label>
                            <select name="role" id="edit_role" class="select">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- STATUS -->
                    <div class="col-md-6">

                        <div class="radio-wrap">
                            <label class="col-form-label">Status</label>
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                    <input type="radio" class="status-radio" id="edit_active" name="status"
                                        value="1">
                                    <label for="edit_active">Active</label>
                                </div>
                                <div>
                                    <input type="radio" class="status-radio" id="edit_inactive" name="status"
                                        value="0">
                                    <label for="edit_inactive">Inactive</label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>

    </div>
    <!-- /Edit User -->

    <!-- Delete User -->
    <div class="modal fade" id="delete_contact" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center">

                        <div class="avatar avatar-xl bg-danger-light rounded-circle mb-3">
                            <i class="ti ti-trash-x fs-36 text-danger"></i>
                        </div>

                        <h4 class="mb-2">Remove user?</h4>
                        <p class="mb-0">Are you sure you want to delete this user?</p>

                        <div class="d-flex align-items-center justify-content-center mt-4">

                            <button class="btn btn-light me-2" data-bs-dismiss="modal">
                                Cancel
                            </button>

                            <!-- ✅ FORM -->
                            <form id="deleteUserForm" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger">
                                    Yes, Delete it
                                </button>
                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Delete User -->

    @push('scripts')
        <script>
            $(document).on('click', '.edit-btn', function() {

                let id = $(this).data('id');

                // set form action dynamically
                $('#editUserForm').attr('action', '/users/' + id);

                // fetch user data
                $.get('/users/' + id + '/edit', function(data) {
                    console.log(data);
                    $('#edit_name').val(data.name);
                    $('#edit_email').val(data.email);
                    $('#edit_role').val(data.role);


                    if (data.status == 1) {
                        $('#edit_active').prop('checked', true);
                    } else {
                        $('#edit_inactive').prop('checked', true);
                    }

                    // 🔥 IMPORTANT (this is the missing part)
                    setImagePreview('edit_profile_image', data.profile_image_url);

                });
            });
        </script>

        <script>
            $(document).on('click', '.delete-btn', function() {

                let userId = $(this).data('id');

                console.log("Deleting user ID:", userId); // debug

                // ✅ IMPORTANT
                $('#deleteUserForm').attr('action', '/users/' + userId);

            });
        </script>

         <script>
            let searchTimer;

            $('#userSearch').on('keyup', function() {

                clearTimeout(searchTimer);

                let value = $(this).val();

                searchTimer = setTimeout(() => {

                    let url = new URL(window.location.href);

                    if (value) {
                        url.searchParams.set('search', value);
                    } else {
                        url.searchParams.delete('search');
                    }

                    window.location.href = url;

                }, 500);

            });
        </script>

    @endpush
@endsection
