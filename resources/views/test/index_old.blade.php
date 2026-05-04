@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="content">

            <x-offcanvas id="projectCanvas" title="Project" formId="projectForm">

                <form id="projectForm" class="ajax-form" data-table="#projectTable" method="POST"
                    action="{{ route('test.store') }}">
                    @csrf
                    @php
                        $config = [
                            [
                                'name' => 'name',
                                'label' => 'Name',
                                'placeholder' => 'Enter project name',
                                'type' => 'text',
                                'required' => true,
                                'col' => 6,
                            ],
                            ['name' => 'website_url', 'label' => 'Website URL', 'type' => 'text', 'col' => 6],
                            ['name' => 'event_name', 'label' => 'Event Name', 'type' => 'text', 'col' => 6],
                            [
                                'name' => 'flow_type',
                                'label' => 'Flow Type',
                                'type' => 'select',
                                'options' => ['simple' => 'Simple', 'full' => 'Full'],
                                'required' => true,
                                'col' => 6,
                            ],

                            [
                                'name' => 'pipedrive_account_id',
                                'label' => 'Pipedrive Account',
                                'type' => 'select',
                                'options' => $pipedriveAccounts,
                                'col' => 6,
                            ],

                            [
                                'name' => 'invoice_account_id',
                                'label' => 'Invoice Account',
                                'type' => 'select',
                                'options' => $invoiceAccounts,
                                'col' => 6,
                            ],
                            [
                                'name' => 'invoice_enabled',
                                'label' => 'Invoice Enabled',
                                'type' => 'checkbox',
                                'col' => 6,
                            ],
                        ];
                    @endphp
                    <x-form.fields :config="$config" />
                </form>

            </x-offcanvas>

            <button class="btn btn-primary open-form" data-url="{{ route('test.store') }}" data-bs-toggle="offcanvas"
                data-bs-target="#projectCanvas" data-form="#projectForm">
                <i class="ti ti-square-rounded-plus me-2"></i>
                Add New Project
            </button>

            <table id="projectTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Website</th>
                        <th>Flow</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>

                <tbody>

                    {{-- for normal table --}}

                    {{-- @foreach ($projects as $project)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $project->name }}</td>
                            <td>{{ $project->website_url }}</td>
                            <td>{{ $project->flow_type }}</td>

                            <td>
                                <button class="btn btn-warning open-form" data-type="edit"
                                    data-url="{{ route('test.update', $project->id) }}" data-method="PUT"
                                    data-data='@json($project)' data-bs-toggle="offcanvas"
                                    data-bs-target="#projectCanvas" data-form="#projectForm">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    @endforeach --}}
                </tbody>
            </table>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                let table = $('#projectTable').DataTable({
                    processing: true,
                    ajax: "/test/list",

                    columns: [{
                            data: null,
                            render: (data, type, row, meta) => meta.row + 1
                        },
                        {
                            data: "name"
                        },
                        {
                            data: "website_url"
                        },
                        {
                            data: "flow_type"
                        },

                        {
                            data: null,
                            render: function(data) {
                                return `
                                <button
                                    class="btn btn-sm btn-warning edit-form"
                                    data-type="edit"
                                    data-url="/test/${data.id}"
                                    data-method="PUT"
                                    data-data='${JSON.stringify(data)}'
                                    data-bs-toggle="offcanvas"
                                    data-bs-target="#projectCanvas"
                                    data-form="#projectForm"
                                >
                                    Edit
                                </button>`;
                            }
                        },
                        {
                            data: null,
                            orderable: false,
                            render: function(data) {
                                return `
                        <button
                            class="btn btn-danger btn-sm delete-btn"
                            data-url="/test/${data.id}"
                        >
                            Delete
                        </button>
                    `;
                            }
                        }
                    ]
                });
            })
        </script>

        @push('scripts')
            <script>
                const projectId = 10;

                // 🔥 Create SWR instance
                const company = swr(
                    `company_${projectId}`,
                    api.get(`/projects/${projectId}/company`)
                );

                console.log(company);
            </script>
        @endpush
    @endpush
@endsection
