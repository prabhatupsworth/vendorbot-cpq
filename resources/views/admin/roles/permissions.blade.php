@extends('layouts.app')

@section('content')

    <style>
        .permission-table-wrapper {
            background: #fff;
            border-radius: 24px;
            padding: 10px;
            box-shadow: 0 10px 40px rgba(15, 23, 42, .06);
            overflow: hidden;
        }

        .permission-table {
            margin: 0;
            border-collapse: separate;
            border-spacing: 0 12px;
        }

        .permission-table thead th {
            border: none;
            background: #f8fafc;
            padding: 14px;
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            /* text-align: center; */
        }

        .permission-table tbody tr {
            background: #fff;
            transition: .3s;
        }

        .permission-table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, .05);
        }

        .permission-table tbody td {
            vertical-align: middle;
            border-top: 1px solid #edf2f7;
            border-bottom: 1px solid #edf2f7;
            padding: 12px 14px;
            text-align: center;
            background: #fff;
        }

        .permission-table tbody td:first-child {
            border-left: 1px solid #edf2f7;
            border-radius: 16px 0 0 16px;
            text-align: left;
        }

        .permission-table tbody td:last-child {
            border-right: 1px solid #edf2f7;
            border-radius: 0 16px 16px 0;
        }

        .module-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .module-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #E41F07, #ff826e);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
            box-shadow: 0 6px 16px rgba(228, 31, 7, .20);
        }

        .module-info h6 {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }

        .module-info span {
            font-size: 12px;
            color: #64748b;
        }

        .permission-head {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .permission-head i {
            width: 30px;
            height: 30px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            color: #fff;
        }

        .permission-head span {
            font-size: 12px;
            font-weight: 600;
        }

        .permission-head.view i {
            background: #3b82f6;
        }

        .permission-head.create i {
            background: #10b981;
        }

        .permission-head.edit i {
            background: #f59e0b;
        }

        .permission-head.delete i {
            background: #ef4444;
        }

        .permission-head.full i {
            background: #8b5cf6;
        }

        .permission-checkbox {
            position: relative;
            display: inline-flex;
            cursor: pointer;
        }

        .permission-checkbox input {
            display: none;
        }

        /* SMALLER CHECKBOX */
        .checkmark {
            width: 20px;
            height: 20px;
            border-radius: 6px;
            border: 1.8px solid #d1d5db;
            transition: .25s ease;
            position: relative;
            background: #fff;
        }

        .permission-checkbox input:checked+.checkmark {
            background: #E41F07;
            border-color: #E41F07;
        }

        .permission-checkbox input:checked+.checkmark::after {
            content: '';
            position: absolute;
            left: 6px;
            top: 2px;
            width: 5px;
            height: 10px;
            border: solid #fff;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        /* SMALLER SWITCH */
        .switch {
            position: relative;
            display: inline-block;
            width: 42px;
            height: 22px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            inset: 0;
            background: #d1d5db;
            border-radius: 50px;
            transition: .3s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 3px;
            top: 3px;
            background: white;
            border-radius: 50%;
            transition: .3s;
        }

        .switch input:checked+.slider {
            background: #E41F07;
        }

        .switch input:checked+.slider:before {
            transform: translateX(20px);
        }

        .not-available {
            color: #cbd5e1;
            font-size: 16px;
        }

        /* MOBILE RESPONSIVE */
        @media (max-width: 768px) {

            .permission-table thead th {
                padding: 10px;
                font-size: 12px;
            }

            .permission-table tbody td {
                padding: 10px 8px;
            }

            .module-icon {
                width: 32px;
                height: 32px;
                font-size: 15px;
            }

            .module-info h6 {
                font-size: 13px;
            }

            .module-info span {
                display: none;
            }

            .permission-head i {
                width: 26px;
                height: 26px;
                font-size: 13px;
            }

            .checkmark {
                width: 18px;
                height: 18px;
            }

            .switch {
                width: 38px;
                height: 20px;
            }

            .slider:before {
                width: 14px;
                height: 14px;
            }

            .switch input:checked+.slider:before {
                transform: translateX(18px);
            }
        }
    </style>
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
                                    <a href="{{ route('roles.permissions', $role->id) }}" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-original-title="Refresh"><i
                                            class="ti ti-refresh-dot"></i></a>
                                    <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-original-title="Collapse" id="collapse-header"><i
                                            class="ti ti-chevrons-up"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->

                    <div class="card">
                        <div class="card-header py-4">
                            <div class="row">
                                <div class="col-md-5 col-sm-4">
                                    <div class="mb-3 mb-sm-0">
                                        <h4>Role Name : <span
                                                class="text-danger ">{{ ucwords(str_replace('_', ' ', $role->name)) }}</span>
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-md-7 col-sm-8">
                                    <div class="text-sm-end">
                                        {{-- <label class="checkboxs d-flex align-items-center justify-content-sm-end"><input
                                                type="checkbox"><span
                                                class="checkmarks position-relative d-flex me-2"></span>Allow All
                                            Modules</label> --}}

                                        <input class="form-check-input" type="checkbox" value="" id="checkebox-md"
                                            checked="">
                                        <label class="form-check-label" for="checkebox-md">
                                            Allow All Modules
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="permission-table-wrapper">

                                <table class="table permission-table align-middle">

                                    <thead>

                                        <tr>

                                            <th class="module-column">
                                                Modules
                                            </th>

                                            <th>
                                                <div class="permission-head view">
                                                    <i class="ti ti-eye"></i>
                                                    <span>View</span>
                                                </div>
                                            </th>

                                            <th>
                                                <div class="permission-head create">
                                                    <i class="ti ti-plus"></i>
                                                    <span>Create</span>
                                                </div>
                                            </th>

                                            <th>
                                                <div class="permission-head edit">
                                                    <i class="ti ti-edit"></i>
                                                    <span>Edit</span>
                                                </div>
                                            </th>

                                            <th>
                                                <div class="permission-head delete">
                                                    <i class="ti ti-trash"></i>
                                                    <span>Delete</span>
                                                </div>
                                            </th>

                                            <th>
                                                <div class="permission-head full">
                                                    <i class="ti ti-shield-check"></i>
                                                    <span>Full Access</span>
                                                </div>
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        @foreach ($modules as $module => $perms)
                                            <tr>

                                                <td>

                                                    <div class="module-info">

                                                        <div class="module-icon">
                                                            <i class="ti ti-package"></i>
                                                        </div>

                                                        <div>
                                                            <h6>{{ ucwords($module) }}</h6>
                                                            <span>
                                                                Manage {{ $module }}
                                                            </span>
                                                        </div>

                                                    </div>

                                                </td>

                                                @php
                                                    $actions = ['view', 'create', 'edit', 'delete'];
                                                @endphp

                                                @foreach ($actions as $action)
                                                    @php
                                                        $permissionName = $module . '.' . $action;
                                                    @endphp

                                                    <td>

                                                        @if (collect($perms)->contains('name', $permissionName))
                                                            <label class="permission-checkbox">

                                                                <input type="checkbox" name="permissions[]"
                                                                    value="{{ $permissionName }}"
                                                                    {{ in_array($permissionName, $rolePermissions) ? 'checked' : '' }}>

                                                                <span class="checkmark"></span>

                                                            </label>
                                                        @else
                                                            <span class="not-available">
                                                                —
                                                            </span>
                                                        @endif

                                                    </td>
                                                @endforeach

                                                <td>

                                                    <label class="switch">

                                                        <input type="checkbox" class="module-check">

                                                        <span class="slider"></span>

                                                    </label>

                                                </td>

                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

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
