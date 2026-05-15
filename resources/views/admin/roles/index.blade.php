@extends('layouts.app')

@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content">

            <div class="row">
                <div class="col-md-12">

                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="page-title">Roles<span class="count-title">List</span></h4>
                            </div>
                            <div class="col-4 text-end">
                                <div class="head-icons">
                                    <a href="{{ route('roles.index') }}" data-bs-toggle="tooltip" data-bs-placement="top"
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
                                        <input type="text" id="search-role" class="form-control"
                                            placeholder="Search Roles...">
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="text-sm-end">

                                        <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#add_role"><i class="ti ti-square-rounded-plus me-2"></i>Add New
                                            Roles</a>
                                    </div>
                                </div>
                            </div>
                            <!-- /Search -->
                        </div>
                        <div class="card-body">
                            <!-- Roles List -->
                            <div class="table-responsive custom-table">
                                <table class="table align-middle">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="60">#</th>
                                            <th>Role Name</th>

                                            <th>Created At</th>

                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody id="role-table-body">
                                        @include('admin.roles.partials.table')
                                    </tbody>
                                </table>
                            </div>
                            <!-- /Roles List -->

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <!-- /Page Wrapper -->

    <!-- Add Role -->
    <div class="modal fade" id="add_role" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Role</h5>
                    <button class="btn-close custom-btn-close border p-1 me-0 text-dark" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <form method="POST" action="{{ route('roles.store') }}">
                    @csrf
                    <input type="hidden" name="_form" value="add">
                    <div class="modal-body">
                        <div class="mb-0">
                            <label class="col-form-label">
                                Role Name <span class="text-danger">*</span>
                            </label>

                            <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                placeholder="Enter role name">

                            {{-- Validation error --}}
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="d-flex align-items-center justify-content-end m-0">
                            <a href="#" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Add Role -->

    <!-- Edit Role -->
    <div class="modal fade" id="edit_role" tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Edit Role
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>

                <form id="editRoleForm" method="POST">

                    @csrf

                    @method('PUT')

                    <input type="hidden" name="id" id="edit_role_id">

                    <div class="modal-body">

                        <div class="mb-3">

                            <label class="form-label">
                                Role Name
                            </label>

                            <input type="text" name="name" id="edit_role_name" class="form-control">

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">

                            Cancel
                        </button>

                        <button type="submit" class="btn btn-primary">

                            Update Role
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
    <!-- /Edit Role -->

    <!-- DELETE MODAL -->
    <div class="modal fade" id="delete_role" tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-body text-center p-4">

                    <div class="mb-3">

                        <div class="avatar avatar-xl bg-danger-light rounded-circle mx-auto">

                            <i class="ti ti-trash text-danger fs-28"></i>

                        </div>

                    </div>

                    <h4 class="mb-2">
                        Delete Role?
                    </h4>

                    <p class="text-muted mb-4">

                        Are you sure you want to delete

                        <strong id="delete_role_name"></strong> ?

                    </p>

                    <div class="d-flex justify-content-center gap-2">

                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">

                            Cancel
                        </button>

                        <form id="deleteRoleForm" method="POST">

                            @csrf

                            @method('DELETE')

                            <button type="submit" class="btn btn-danger">

                                Yes, Delete
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

    @push('scripts')
        <script>
            $(document).on('click', '.edit-role', function() {

                let id = $(this).data('id');

                let name = $(this).data('name');

                // set values
                $('#edit_role_id').val(id);

                $('#edit_role_name').val(name);

                // set form action
                $('#editRoleForm').attr(
                    'action',
                    '/roles/' + id
                );

                // open modal
                $('#edit_role').modal('show');

            });


            // DELETE ROLE
            $(document).on('click', '.delete-role', function() {

                let id = $(this).data('id');

                let name = $(this).data('name');

                $('#delete_role_name').text(name);

                $('#deleteRoleForm').attr(
                    'action',
                    '/roles/' + id
                );

                $('#delete_role').modal('show');

            });
        </script>
        <script>
            let debounceTimer;

            $('#search-role').on('keyup', function() {

                clearTimeout(debounceTimer);

                let search = $(this).val();

                debounceTimer = setTimeout(() => {

                    $.ajax({
                        url: "{{ route('roles.index') }}",
                        type: "GET",
                        data: {
                            search: search
                        },

                        beforeSend: function() {

                            $('#role-table-body').html(`
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                Loading...
                            </td>
                        </tr>
                    `);

                        },

                        success: function(response) {

                            $('#role-table-body').html(response.html);

                        }

                    });

                }, 500);

            });
        </script>

        @if ($errors->any())
            <script>
                document.addEventListener("DOMContentLoaded", function() {

                    let formType = "{{ old('_form') }}";

                    if (formType === 'add') {
                        var addModal = new bootstrap.Modal(document.getElementById('add_role'));
                        addModal.show();
                    }

                    if (formType === 'edit') {
                        var editModal = new bootstrap.Modal(document.getElementById('edit_role'));
                        editModal.show();
                    }
                });
            </script>
        @endif
    @endpush
@endsection
