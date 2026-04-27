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
                                <h4 class="page-title">Permission <span class="count-title">List</span></h4>
                            </div>
                            <div class="col-4 text-end">
                                <div class="head-icons">
                                    <a href="{{ route('roles.permissions', $role->id) }}" data-bs-toggle="tooltip" data-bs-placement="top"
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
                            <div class="row">
                                <div class="col-md-5 col-sm-4">
                                    <div class="mb-3 mb-sm-0">
                                        <h4>Role Name : <span class="text-danger ">{{ ucwords(str_replace('_', ' ', $role->name)) }}</span></h4>
                                    </div>
                                </div>
                                <div class="col-md-7 col-sm-8">
                                    <div class="text-sm-end">
                                        <label class="checkboxs d-flex align-items-center justify-content-sm-end"><input
                                                type="checkbox"><span
                                                class="checkmarks position-relative d-flex me-2"></span>Allow All
                                            Modules</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Roles List -->
                            <div class="table-responsive custom-table">
                                <table class="table" id="permission_list_new">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Modules</th>
                                            <th>Create</th>
                                            <th>Edit</th>
                                            <th>View</th>
                                            <th>Delete</th>
                                            <th class="no-sort">Allow Row</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($modules as $module => $perms)
                                            <tr>

                                                <td class="text-capitalize">{{ $module }}</td>

                                                @php
                                                    $actions = ['create', 'edit', 'view', 'delete'];
                                                @endphp

                                                @foreach ($actions as $action)
                                                    @php
                                                        $permissionName = $module . '.' . $action;
                                                    @endphp

                                                    <td>
                                                        @if (collect($perms)->contains('name', $permissionName))
                                                            <label class="checkboxs">
                                                                <input type="checkbox" name="permissions[]"
                                                                    value="{{ $permissionName }}"
                                                                    {{ in_array($permissionName, $rolePermissions) ? 'checked' : '' }}>
                                                                <span class="checkmarks"></span>
                                                            </label>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                @endforeach

                                                <td>
                                                    <label class="checkboxs">
                                                        <input type="checkbox" class="module-check">
                                                        <span class="checkmarks"></span>
                                                    </label>
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
                            <!-- /Roles List -->

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {

                $('#permission_list_new').DataTable({
                    bFilter: false,
                    bInfo: false,
                    ordering: true,
                    autoWidth: true,

                    ajax: {
                        url: `/roles/${roleId}/permission-data`,
                        type: "GET",
                        dataSrc: ""
                    },

                    columns: [

                        // ✅ Row checkbox
                        {
                            orderable: false,
                            render: function() {
                                return `<label class="checkboxs">
                    <input type="checkbox" class="module-check">
                    <span class="checkmarks"></span>
                </label>`;
                            }
                        },

                        // Module
                        {
                            data: "module"
                        },

                        // CREATE
                        {
                            render: function(data, type, row) {
                                return checkbox(row, 'create');
                            }
                        },

                        // EDIT
                        {
                            render: function(data, type, row) {
                                return checkbox(row, 'edit');
                            }
                        },

                        // VIEW
                        {
                            render: function(data, type, row) {
                                return checkbox(row, 'view');
                            }
                        },

                        // DELETE
                        {
                            render: function(data, type, row) {
                                return checkbox(row, 'delete');
                            }
                        },

                        // Allow Row
                        {
                            orderable: false,
                            render: function() {
                                return `<label class="checkboxs">
                    <input type="checkbox" class="allow-row">
                    <span class="checkmarks"></span>
                </label>`;
                            }
                        }
                    ]
                });

                // ✅ reusable checkbox
                function checkbox(row, action) {

                    if (row.actions[action] === undefined) return '-';

                    return `<label class="checkboxs">
        <input type="checkbox"
            name="permissions[]"
            value="${row.module_key}.${action}"
            ${row.actions[action] ? 'checked="checked"' : ''}>
        <span class="checkmarks"></span>
    </label>`;
                }
            });
        </script>


        @push('scripts')
            <script>
                $(document).ready(function() {

                    let roleId = "{{ $role->id }}";

                    const allowAll = $('.card-header input[type="checkbox"]');

                    function allPermissionCheckboxes() {
                        return $('tbody input[name="permissions[]"]');
                    }

                    // ================================
                    // ✅ THEME UI SYNC
                    // ================================
                    function syncCheckboxUI() {
                        $('input[type="checkbox"]').each(function() {
                            $(this).closest('.checkboxs')
                                .toggleClass('active', $(this).is(':checked'));
                        });
                    }

                    // ================================
                    // ✅ GLOBAL ALLOW ALL SYNC
                    // ================================
                    function syncAllowAll() {
                        let total = allPermissionCheckboxes().length;
                        let checked = allPermissionCheckboxes().filter(':checked').length;

                        allowAll.prop('checked', total === checked);
                    }

                    // ================================
                    // ✅ ROW AUTO SYNC
                    // ================================
                    function syncRowCheckbox(row) {
                        let rowCheckboxes = row.find('input[name="permissions[]"]');
                        let checkedCount = rowCheckboxes.filter(':checked').length;

                        row.find('.module-check').prop(
                            'checked',
                            checkedCount === rowCheckboxes.length
                        );
                    }

                    // ================================
                    // ✅ AUTO SAVE (MAIN LOGIC)
                    // ================================
                    $(document).on('change', 'input[name="permissions[]"]', function() {

                        let checkbox = $(this);
                        let permission = checkbox.val();
                        let checked = checkbox.is(':checked') ? 1 : 0;

                        $.ajax({
                            url: `/roles/${roleId}/toggle-permission`,
                            type: "POST",
                            data: {
                                permission: permission,
                                checked: checked,
                                _token: "{{ csrf_token() }}"
                            }
                        });

                        let row = checkbox.closest('tr');

                        // sync row + global
                        syncRowCheckbox(row);
                        syncAllowAll();
                        syncCheckboxUI();
                    });

                    // ================================
                    // ✅ ROW SELECT (MODULE)
                    // ================================
                    $(document).on('change', '.module-check', function() {

                        let checked = $(this).is(':checked');
                        let row = $(this).closest('tr');

                        row.find('input[name="permissions[]"]').each(function() {
                            $(this).prop('checked', checked).trigger('change');
                        });

                        syncCheckboxUI();
                    });

                    // ================================
                    // ✅ GLOBAL SELECT ALL
                    // ================================
                    allowAll.on('change', function() {

                        let checked = $(this).is(':checked');

                        allPermissionCheckboxes().each(function() {
                            $(this).prop('checked', checked).trigger('change');
                        });

                        $('.module-check').prop('checked', checked);

                        syncCheckboxUI();
                    });

                    // ================================
                    // ✅ INITIAL LOAD SYNC
                    // ================================
                    $('tbody tr').each(function() {
                        syncRowCheckbox($(this));
                    });

                    syncAllowAll();
                    syncCheckboxUI();

                });
            </script>
        @endpush
    @endpush
@endsection
