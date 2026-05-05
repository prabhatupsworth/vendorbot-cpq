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
                            <div class="col-4">
                                <h4 class="page-title">Projects<span class="count-title">123</span></h4>
                            </div>
                            <div class="col-8 text-end">
                                <div class="head-icons">
                                    <a href="projects.html" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-original-title="Refresh">
                                        <i class="ti ti-refresh-dot"></i>
                                    </a>
                                    <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-original-title="Collapse" id="collapse-header">
                                        <i class="ti ti-chevrons-up"></i>
                                    </a>
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
                                        <input type="text" class="form-control" placeholder="Search Projects">
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="d-flex align-items-center flex-wrap row-gap-2 justify-content-sm-end">
                                        <div class="dropdown me-2">
                                            <a href="javascript:void(0);" class="dropdown-toggle"
                                                data-bs-toggle="dropdown"><i
                                                    class="ti ti-package-export me-2"></i>Export</a>
                                            <div class="dropdown-menu  dropdown-menu-end">
                                                <ul>
                                                    <li>
                                                        <a href="javascript:void(0);" class="dropdown-item"><i
                                                                class="ti ti-file-type-pdf text-danger me-1"></i>Export
                                                            as PDF</a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0);" class="dropdown-item"><i
                                                                class="ti ti-file-type-xls text-green me-1"></i>Export
                                                            as Excel </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="offcanvas"
                                            data-bs-target="#offcanvas_add"><i
                                                class="ti ti-square-rounded-plus me-2"></i>Add New Project</a>
                                    </div>
                                </div>
                            </div>
                            <!-- /Search -->
                        </div>

                        <div class="card-body">

                            <!-- Projects List -->
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Project Name</th>
                                            <th>Event Name</th>
                                            <th>Flow Type</th>
                                            <th>Invoice Enabled</th>
                                            <th>Pipedrive Account</th>
                                            <th>Invoice Account</th>
                                            {{-- <th>Pipedrive Sync Status</th>
                                            <th>Plugin Connected At</th>
                                            <th>Plugin Last Ping At</th>
                                            <th>Plugin Connected</th> --}}
                                            <th>Created At</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($projects as $project)
                                            <tr>
                                                <td>{{ $project->name }}</td>
                                                <td>{{ $project->event_name }}</td>
                                                <td>{{ $project->flow_type }}</td>
                                                <td>{{ $project->invoice_enabled ? 'Yes' : 'No' }}</td>
                                                <td>{{ $project->pipedriveAccount ? $project->pipedriveAccount->account_name : 'N/A' }}
                                                </td>
                                                <td>{{ $project->invoiceAccount ? $project->invoiceAccount->type : 'N/A' }}
                                                </td>
                                                {{-- Sync Status --}}
                                                {{-- <td>
                                                    @if ($project->pipedrive_sync_status)
                                                        <span class="badge bg-success">Synced</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">Not Synced</span>
                                                    @endif
                                                </td> --}}

                                                {{-- Connected At --}}
                                                {{-- <td>
                                                    {{ $project->plugin_connected_at ? $project->plugin_connected_at->format('d M Y, h:i A') : '-' }}
                                                </td> --}}

                                                {{-- Last Ping --}}
                                                {{-- <td>
                                                    {{ $project->plugin_last_ping_at ? $project->plugin_last_ping_at->diffForHumans() : 'Never' }}
                                                </td> --}}

                                                {{-- Plugin Status --}}
                                                {{-- <td>
                                                    @if ($project->plugin_connected)
                                                        <span class="badge bg-success">Connected</span>
                                                    @else
                                                        <span class="badge bg-danger">Disconnected</span>
                                                    @endif
                                                </td> --}}
                                                <td>{{ $project->created_at->format('Y-m-d H:i:s') }}</td>
                                                <td class="text-end">
                                                    <div class="dropdown table-action">
                                                        <a href="#" class="action-icon show" data-bs-toggle="dropdown"
                                                            aria-expanded="true"><i class="fa fa-ellipsis-v"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right"
                                                            style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(-104px, 35px, 0px);"
                                                            data-popper-placement="bottom-start"
                                                            data-popper-reference-hidden="" data-popper-escaped="">
                                                            {{-- <a class="dropdown-item viewProject"
                                                                data-id="{{ $project->id }}" data-bs-toggle="offcanvas"
                                                                data-bs-target="#offcanvas_view_project" href="#"><i
                                                                    class="ti ti-eye text-success"></i> View</a> --}}
                                                            <a class="dropdown-item"
                                                                href="{{ route('projects.show', $project->id) }}"><i
                                                                    class="ti ti-eye text-success"></i> View</a>

                                                            <a class="dropdown-item editProject" data-bs-toggle="offcanvas"
                                                                data-bs-target="#offcanvas_add"
                                                                data-id="{{ $project->id }}" href="#"><i
                                                                    class="ti ti-edit text-blue"></i> Edit</a>
                                                            <a class="dropdown-item deleteProject" href="#"
                                                                data-bs-toggle="modal" data-bs-target="#delete_project"
                                                                data-id="{{ $project->id }}"><i
                                                                    class="ti ti-trash text-danger"></i> Delete</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /Projects List -->

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <!-- /Page Wrapper -->

    <!-- Add Edit Project -->
    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_add">
        <div class="offcanvas-header border-bottom">
            <h4 id="offcanvas-title">Add New Project</h4>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body">
            <form action="{{ route('projects.store') }}" method="POST">
                @csrf

                <div class="row">

                    {{-- NAME --}}
                    <div class="col-md-6 mb-3">
                        <label class="col-form-label">Name <span class="text-danger">*</span></label>
                        <input placeholder="Enter project name" type="text" name="name" class="form-control"
                            required>
                    </div>

                    {{-- WEBSITE URL --}}
                    <div class="col-md-6 mb-3">
                        <label class="col-form-label">Website URL</label>
                        <input placeholder="Enter website URL" type="text" name="website_url" class="form-control">
                    </div>

                    {{-- EVENT NAME --}}
                    <div class="col-md-6 mb-3">
                        <label class="col-form-label">Event Name</label>
                        <input placeholder="Enter event name" type="text" name="event_name" class="form-control">
                    </div>

                    {{-- FLOW TYPE --}}
                    <div class="col-md-6 mb-3">
                        <label class="col-form-label">Flow Type <span class="text-danger">*</span></label>
                        <select name="flow_type" class="form-select" required>
                            <option value="simple" selected>Simple</option>
                            <option value="full">Full</option>
                        </select>
                    </div>

                    {{-- INVOICE ENABLED --}}
                    <div class="col-md-6 mb-3">
                        <label class="col-form-label">Invoice Enabled</label>
                        <select name="invoice_enabled" class="form-select">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>

                    {{-- PIPEDRIVE ACCOUNT --}}
                    <div class="col-md-6 mb-3">
                        <label class="col-form-label">Pipedrive Account</label>
                        <select name="pipedrive_account_id" class="form-select">
                            <option value="">Select</option>
                            @foreach ($pipedriveAccounts as $id => $account_name)
                                <option value="{{ $id }}">{{ $account_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="col-form-label">Pipeline</label>
                        <select name="pipeline_id" id="pipelineSelect" class="form-select">
                            <option value="">Select Pipeline</option>
                        </select>
                    </div>

                    {{-- INVOICE ACCOUNT --}}
                    <div class="col-md-6 mb-3">
                        <label class="col-form-label">Invoice Account</label>
                        <select name="invoice_account_id" class="form-select">
                            <option value="">Select</option>
                            @foreach ($invoiceAccounts as $id => $type)
                                <option value="{{ $id }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="text-end mt-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="offcanvas">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /Add Edit Project -->

    {{-- View Project --}}
    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_view_project">
        <div class="offcanvas-header border-bottom">
            <h5 class="fw-semibold">Project Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body">
            <div class="card">
                <div class="card-body">

                    <!-- 🔹 Tabs -->
                    <ul class="nav nav-tabs nav-tabs-bottom mb-3">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#project-overview">Overview</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#project-status">Status</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#project-history">History</a>
                        </li>
                    </ul>

                    <div class="tab-content">

                        <!-- ✅ OVERVIEW -->
                        <div class="tab-pane show active" id="project-overview">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="text-capitalize" id="p_name">--</h5>
                                    <small class="text-muted" id="p_slug">--</small>
                                </div>

                                {{-- <div id="p_status">
                                    <span class="badge bg-secondary">--</span>
                                </div> --}}
                            </div>

                            <hr>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-light">
                                        <small>Website</small>
                                        <p id="p_website">--</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-light">
                                        <small>Event</small>
                                        <p id="p_event">--</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-light">
                                        <small>Flow Type</small>
                                        <p id="p_flow">--</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-light">
                                        <small>Invoice</small>
                                        <p id="p_invoice">--</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-light">
                                        <small>Pipedrive</small>
                                        <p id="p_pipedrive">--</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-light">
                                        <small>Invoice Account</small>
                                        <p id="p_invoice_acc">--</p>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- ✅ STATUS -->
                        <div class="tab-pane" id="project-status">
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-light">
                                        <small>Pipedrive Sync Status</small>
                                        <p id="p_sync"></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-light">
                                        <small>Plugin Status</small>
                                        <p id="p_plugin"></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-light">
                                        <small>Plugin Connected At</small>
                                        <p id="p_connected"></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-light">
                                        <small>Plugin Last Ping</small>
                                        <p id="p_ping"></p>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- ✅ HISTORY -->
                        <div class="tab-pane" id="project-history">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Message</th>
                                            <th>User</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody id="projectHistoryBody"></tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <a id="load_more" class="btn btn-danger nav-link mt-2" href="#">Load
                                    More</a>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Delete Project -->
    <div class="modal fade" id="delete_project" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center">
                        <div class="avatar avatar-xl bg-danger-light rounded-circle mb-3">
                            <i class="ti ti-trash-x fs-36 text-danger"></i>
                        </div>
                        <h4 class="mb-2">Remove Project?</h4>
                        <p class="mb-0">Are you sure you want to remove <br> project you selected.</p>
                        <div class="d-flex align-items-center justify-content-center mt-4">
                            <a href="#" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</a>
                            <form id="deleteForm" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Yes, Delete it</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Delete Project -->

    @push('scripts')
        <script>
            // edit project with jqueery ajax
            $(document).on('click', '.editProject', function() {
                $('#offcanvas-title').text('Edit Project');
                let projectId = $(this).data('id');
                $.ajax({
                    url: '/projects/' + projectId + '/edit',
                    method: 'GET',
                    success: function(response) {
                        // Populate the edit form with the project details
                        $('#offcanvas_add input[name="name"]').val(response.name);
                        $('#offcanvas_add input[name="website_url"]').val(response.website_url);
                        $('#offcanvas_add input[name="event_name"]').val(response.event_name);
                        $('#offcanvas_add select[name="flow_type"]').val(response.flow_type);
                        $('#offcanvas_add select[name="invoice_enabled"]').val(response.invoice_enabled ?
                            '1' : '0');
                        $('#offcanvas_add select[name="pipedrive_account_id"]').val(response
                            .pipedrive_account_id);
                        $('#offcanvas_add select[name="invoice_account_id"]').val(response
                            .invoice_account_id);
                        let form = $('#offcanvas_add form');

                        form.attr('action', '/projects/' + projectId + '/update');

                        // remove old _method if exists
                        form.find('input[name="_method"]').remove();

                        // add PUT method
                        form.append('<input type="hidden" name="_method" value="PUT">');

                        // change button text
                        form.find('button[type="submit"]').text('Update');

                    },
                    error: function(xhr) {
                        alert('Failed to fetch project details.');
                    }
                });
            });
        </script>


        <script>
            $(document).on('click', '.viewProject', function() {

                let id = $(this).data('id');

                $.ajax({
                    url: '/projects/' + id,
                    method: 'GET',
                    success: function(res) {
                        console.log(res.project);
                        $('#p_name').text(res?.project?.name);
                        $('#p_slug').text(res?.project?.slug);
                        $('#p_website').text(res?.project?.website_url ?? '-');
                        $('#p_event').text(res?.project?.event_name ?? '-');

                        $('#p_flow').html(
                            `<span class="badge bg-primary">${res?.project?.flow_type}</span>`);

                        $('#p_invoice').html(
                            res?.project?.invoice_enabled ?
                            '<span class="badge bg-success">Enabled</span>' :
                            '<span class="badge bg-secondary">Disabled</span>'
                        );

                        $('#p_pipedrive').text(res?.project.pipedrive_account?.account_name ?? '-');
                        $('#p_invoice_acc').text(res?.project?.invoice_account?.type ?? '-');

                        $('#p_sync').html(
                            res?.project?.pipedrive_sync_status ?
                            '<span class="badge bg-success">Synced</span>' :
                            '<span class="badge bg-warning">Not Synced</span>'
                        );

                        $('#p_plugin').html(
                            res?.project?.plugin_connected ?
                            '<span class="badge bg-success">Connected</span>' :
                            '<span class="badge bg-danger">Disconnected</span>'
                        );

                        $('#p_connected').text(res?.project?.plugin_connected_at ?? '-');
                        $('#p_ping').text(res?.project?.plugin_last_ping_at ?? 'Never');

                        // Populate history table
                        let historyHtml = '';
                        res?.activityLog.forEach(function(log) {
                            historyHtml += `<tr>
                                    <td><span class="badge bg-info">${log.status}</span></td>
                                    <td>${log.message}</td>
                                    <td>${log.user ? log.user.name : 'System'}</td>
                                    <td>${new Date(log.performed_at).toLocaleString()}</td>
                                </tr>`;
                        });
                        $('#projectHistoryBody').html(historyHtml);
                        $('#load_more').attr('href', `/history/projects/${res?.project?.id}`);
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to fetch Project details. Please try again.',
                            confirmButtonColor: '#d33'
                        });

                    }
                });

            });
        </script>


        {{-- delete project --}}
        <script>
            $(document).on('click', '.deleteProject', function() {
                let id = $(this).data('id');

                $('#deleteForm').attr('action', '/projects/' + id);
            });

            $(document).on('change', 'select[name="pipedrive_account_id"]', function() {

                let accountId = $(this).val();

                let pipelineSelect = $('#pipelineSelect');

                pipelineSelect.html('<option>Loading...</option>');

                if (!accountId) {
                    pipelineSelect.html('<option value="">Select Pipeline</option>');
                    return;
                }

                $.ajax({
                    url: `settings/pipedrive/${accountId}/pipelines`,
                    method: 'GET',
                    success: function(res) {

                        let options = '<option value="">Select Pipeline</option>';

                        Object.entries(res.data).forEach(([id, name]) => {
                            options += `<option value="${id}">${name}</option>`;
                        });

                        pipelineSelect.html(options);
                    },
                    error: function() {
                        pipelineSelect.html('<option>Error loading</option>');
                    }
                });

            });
        </script>
    @endpush
@endsection
