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
                                            data-bs-target="#projectCanvas"><i
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

                                                            <a href="#" class="dropdown-item edit-form"
                                                                data-bs-toggle="offcanvas" data-bs-target="#projectCanvas"
                                                                data-type="edit"
                                                                data-url="{{ route('projects.update', $project->id) }}"
                                                                data-method="PUT" data-data='@json($project)'
                                                                data-form="#projectForm">
                                                                <i class="ti ti-edit text-blue"></i> Edit
                                                            </a>
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
    <x-offcanvas id="projectCanvas" title="Project" formId="projectForm">

        <form id="projectForm" class="ajax-form" method="POST" action="{{ route('projects.store') }}">

            @csrf

            @php
                $config = [
                    [
                        'name' => 'name',
                        'label' => 'Name',
                        'type' => 'text',
                        'placeholder' => 'Enter project name',
                        'required' => true,
                        'col' => 6,
                    ],

                    [
                        'name' => 'website_url',
                        'label' => 'Website URL',
                        'type' => 'text',
                        'placeholder' => 'Enter website URL',
                        'col' => 6,
                    ],

                    [
                        'name' => 'event_name',
                        'label' => 'Event Name',
                        'type' => 'text',
                        'placeholder' => 'Enter event name',
                        'col' => 6,
                    ],

                    [
                        'name' => 'flow_type',
                        'label' => 'Flow Type',
                        'type' => 'select',
                        'options' => [
                            'simple' => 'Simple',
                            'full' => 'Full',
                        ],
                        'required' => true,
                        'col' => 6,
                    ],

                    [
                        'name' => 'pipedrive_account_id',
                        'label' => 'Pipedrive Account',
                        'type' => 'select',
                        'options' => $pipedriveAccounts ?? [],
                        'col' => 6,
                    ],

                    // 🔥 pipeline (dynamic)
                    [
                        'name' => 'pipeline_id',
                        'label' => 'Pipeline',
                        'type' => 'select',
                        'options' => [], // loaded via JS
                        'col' => 6,
                        'id' => 'pipelineSelect',
                    ],

                    [
                        'name' => 'invoice_account_id',
                        'label' => 'Invoice Account',
                        'type' => 'select',
                        'options' => $invoiceAccounts ?? [],
                        'col' => 6,
                    ],
                    [
                        'name' => 'invoice_enabled',
                        'label' => 'Invoice Enabled',
                        'type' => 'checkbox',
                        'col' => 12,
                    ],
                ];
            @endphp

            <x-form.fields :config="$config" />

        </form>

    </x-offcanvas>
    <!-- /Add Edit Project -->

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
            $(document).on('change', 'select[name="pipedrive_account_id"]', function() {

                let accountId = $(this).val();
                let pipelineSelect = $(this).closest('form').find('#pipelineSelect');
                console.log(pipelineSelect);

                pipelineSelect.html('<option>Loading...</option>');

                if (!accountId) {
                    pipelineSelect.html('<option value="">Select Pipeline</option>');
                    pipelineSelect.trigger('change'); // 🔥
                    return;
                }

                $.get(`/settings/pipedrive/${accountId}/pipelines`, function(res) {

                    let options = '<option value="">Select Pipeline</option>';

                    Object.entries(res.data).forEach(([id, name]) => {
                        options += `<option value="${id}">${name}</option>`;
                    });

                    pipelineSelect.html(options);

                    // 🔥 set selected value AFTER options loaded
                    let selectedPipeline = pipelineSelect.data("selected");

                    if (selectedPipeline) {
                        pipelineSelect.val(selectedPipeline);
                    }

                    pipelineSelect.trigger('change');
                });

            });
        </script>
    @endpush
@endsection
