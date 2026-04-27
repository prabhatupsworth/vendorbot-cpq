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
                                        <input type="text" class="form-control" placeholder="Search Roles">
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
                                <table class="table" id="roles_list_new">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="no-sort">
                                                <label class="checkboxs">
                                                    <input type="checkbox" id="select-all"><span class="checkmarks"></span>
                                                </label>
                                            </th>
                                            <th>Role Name</th>
                                            <th>Created at</th>
                                            <th class="no-sort">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

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
    <div class="modal fade" id="edit_role" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Role</h5>
                    <button class="btn-close custom-btn-close border p-1 me-0 text-dark" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <form method="POST" id="editRoleForm">
                    @csrf
                    @method('POST') {{-- since your route is POST --}}
                    <input type="hidden" name="_form" value="edit">
                    <input type="hidden" name="id" id="edit_role_id">

                    <div class="modal-body">
                        <div class="mb-0">
                            <label class="col-form-label">
                                Role Name <span class="text-danger">*</span>
                            </label>

                            <input type="text" name="name" id="edit_role_name" class="form-control"
                                value="{{ old('name') }}">

                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="d-flex align-items-center justify-content-end m-0">
                            <a href="#" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Edit Role -->

    @push('scripts')
        <script>
            if ($('#roles_list_new').length > 0) {
                $('#roles_list_new').DataTable({
                    processing: true,
                    serverSide: false, // we are sending all data at once
                    ajax: {
                        url: "/roles/data",
                        type: "GET"
                    },

                    bFilter: false,
                    bInfo: false,
                    ordering: true,
                    autoWidth: true,

                    language: {
                        search: ' ',
                        sLengthMenu: '_MENU_',
                        searchPlaceholder: "Search",
                        info: "_START_ - _END_ of _TOTAL_ items",
                        lengthMenu: "Show _MENU_ entries",
                        paginate: {
                            next: 'Next <i class="fa fa-angle-right"></i>',
                            previous: '<i class="fa fa-angle-left"></i> Prev'
                        },
                    },

                    initComplete: function() {
                        $('.dataTables_paginate').appendTo('.datatable-paginate');
                        $('.dataTables_length').appendTo('.datatable-length');
                    },

                    columns: [{
                            render: function() {
                                return `<label class="checkboxs">
                                <input type="checkbox">
                                <span class="checkmarks"></span>
                            </label>`;
                            }
                        },
                        {
                            data: "roles_name"
                        },
                        {
                            data: "created"
                        },
                        {
                            render: function(data, type, row) {
                                return `
                    <div class="dropdown table-action">
                        <a href="#" class="action-icon" data-bs-toggle="dropdown">
                            <i class="fa fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item edit-role" data-id="${row.id}">
                                <i class="ti ti-edit text-blue"></i> Edit
                            </a>
                            <a class="dropdown-item" href="/roles/${row.id}/permissions">
                                <i class="ti ti-shield text-success"></i> Permission
                            </a>
                        </div>
                    </div>`;
                            }
                        }
                    ]
                });
            }
        </script>

        <script>
            $(document).on('click', '.edit-role', function() {

                let id = $(this).data('id');

                let table = $('#roles_list_new').DataTable();
                let rowData = table.rows().data().toArray().find(r => r.id == id);

                // set values
                $('#edit_role_id').val(id);
                $('#edit_role_name').val(rowData.roles_name);

                // set form action
                $('#editRoleForm').attr('action', '/roles/' + id);

                // open modal
                var modal = new bootstrap.Modal(document.getElementById('edit_role'));
                modal.show();
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
