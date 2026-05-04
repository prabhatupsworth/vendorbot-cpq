@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="content">

            <button class="btn btn-primary open-form" data-url="{{ route('test.store') }}" data-bs-toggle="offcanvas"
                data-bs-target="#projectCanvas" data-form="#projectForm">
                <i class="ti ti-square-rounded-plus me-2"></i>
                Add New Project
            </button>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Website</th>
                            <th>Flow</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>

                    <tbody data-swr="projects" data-url="/test/list" data-list="projects">
                        <template data-template>
                            <tr>
                                <td data-bind="name"></td>
                                <td data-bind="website_url"></td>
                                <td data-bind="flow_type"></td>
                                <td>
                                <td>
                                    <button class="swr-edit-btn" data-form="#projectForm" data-bind-url="/test/{id}"
                                        data-bind-action="/test/{id}">
                                        Edit
                                    </button>

                                    {{-- <button class="delete-btn" data-bind-attr="data-id:id">
                                        Delete
                                    </button> --}}
                                </td>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

        </div>

        <x-offcanvas id="projectCanvas" title="Project" formId="projectForm">

            <form method="POST" id="projectForm" class="swr-form" data-swr="projects" action="{{ route('test.store') }}">
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
    </div>
@endsection
