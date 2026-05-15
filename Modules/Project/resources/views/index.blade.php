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
                                <h4 class="page-title">Projects<span class="count-title">{{ count($projects) }}</span></h4>
                            </div>
                            <div class="col-8 text-end">
                                <div class="head-icons">
                                    <a href="{{ route('projects.index') }}" data-bs-toggle="tooltip" data-bs-placement="top"
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
                                        <input type="text" id="projectSearch" class="form-control"
                                            placeholder="Search Projects" value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="d-flex align-items-center flex-wrap row-gap-2 justify-content-sm-end">
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
                                            <th>Created At</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    {{-- <tbody>
                                        @foreach ($projects as $project)
                                            @include('project::partials.list', ['project' => $project])
                                        @endforeach
                                    </tbody> --}}

                                    <tbody id="project-table-body">

                                        @include('project::partials.table')

                                    </tbody>
                                </table>
                            </div>

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

        <script>
            let searchTimer;

            $('#projectSearch').on('keyup', function() {

                clearTimeout(searchTimer);

                let search = $(this).val();

                searchTimer = setTimeout(() => {

                    $.ajax({

                        url: "{{ route('projects.index') }}",

                        type: "GET",

                        data: {
                            search: search
                        },

                        beforeSend: function() {

                            $('#project-table-body').html(`

                        <tr>

                            <td colspan="8"
                                class="text-center py-5">

                                <div class="spinner-border spinner-border-sm text-primary me-2"></div>

                                Loading Projects...

                            </td>

                        </tr>

                    `);

                        },

                        success: function(response) {

                            $('#project-table-body')
                                .html(response.html);

                        },

                        error: function() {

                            $('#project-table-body').html(`

                        <tr>

                            <td colspan="8"
                                class="text-center text-danger py-5">

                                Failed to load data

                            </td>

                        </tr>

                    `);

                        }

                    });

                }, 500);

            });
        </script>
    @endpush
@endsection
