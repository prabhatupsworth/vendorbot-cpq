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
                                <h4 class="page-title">Categories<span class="count-title"></span></h4>
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
                                            placeholder="Search Categories" value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="d-flex align-items-center flex-wrap row-gap-2 justify-content-sm-end">
                                        <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="offcanvas"
                                            data-bs-target="#categoriesCanvas"><i
                                                class="ti ti-square-rounded-plus me-2"></i>Categories</a>
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

                                            <th>#</th>

                                            <th>Category Name</th>

                                            <th>Project</th>

                                            <th>Selection Type</th>

                                            <th>Required</th>

                                            <th>Tabs</th>

                                            <th>Status</th>

                                            <th>Created At</th>

                                            <th class="text-end">
                                                Actions
                                            </th>

                                        </tr>

                                    </thead>
                                    <tbody>

                                        @forelse($categories as $category)
                                            <tr>

                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>

                                                <td>

                                                    <div class="fw-semibold">

                                                        {{ $category->name }}

                                                    </div>

                                                    @if ($category->description)
                                                        <small class="text-muted">

                                                            {{ \Illuminate\Support\Str::limit($category->description, 50) }}

                                                        </small>
                                                    @endif

                                                </td>

                                                <td>

                                                    {{ $category->project?->name ?? '-' }}

                                                </td>

                                                <td>

                                                    @if ($category->selection_type == 'single')
                                                        <span class="badge badge-soft-success">

                                                            Single

                                                        </span>
                                                    @else
                                                        <span class="badge badge-soft-info">

                                                            Multiple

                                                        </span>
                                                    @endif

                                                </td>

                                                <td>

                                                    @if ($category->is_required)
                                                        <span class="badge badge-soft-danger">

                                                            Required

                                                        </span>
                                                    @else
                                                        <span class="badge badge-soft-secondary">

                                                            Optional

                                                        </span>
                                                    @endif

                                                </td>

                                                <td>

                                                    @if ($category->has_tabs)
                                                        <span class="badge badge-soft-primary">

                                                            Enabled

                                                        </span>
                                                    @else
                                                        <span class="badge badge-soft-warning">

                                                            Disabled

                                                        </span>
                                                    @endif

                                                </td>

                                                <td>

                                                    @if ($category->active)
                                                        <span class="badge badge-soft-success">

                                                            Active

                                                        </span>
                                                    @else
                                                        <span class="badge badge-soft-danger">

                                                            Inactive

                                                        </span>
                                                    @endif

                                                </td>

                                                <td>

                                                    {{ $category->created_at?->format('d M Y') }}

                                                </td>

                                                <td>

                                                    <div class="dropdown table-action">

                                                        <a href="javascript:void(0);" class="action-icon"
                                                            data-bs-toggle="dropdown">

                                                            <i class="fa fa-ellipsis-v"></i>

                                                        </a>

                                                        <div class="dropdown-menu dropdown-menu-right">

                                                            <a href="javascript:void(0);" class="dropdown-item edit-form" data-bs-toggle="offcanvas"
                                                                data-bs-target="#categoriesCanvas" data-type="edit"
                                                                data-url="{{ route('products.categories.update', $category->id) }}"
                                                                data-method="PUT" data-data='@json($category)'
                                                                data-form="#categoryForm">

                                                                <i class="ti ti-edit text-blue"></i>

                                                                Edit

                                                            </a>

                                                            <form method="POST"
                                                                action="{{ route('products.categories.destroy', $category->id) }}">

                                                                @csrf
                                                                @method('DELETE')

                                                                <button type="submit" class="dropdown-item confirm-delete">

                                                                    <i class="ti ti-trash text-danger"></i>

                                                                    Delete

                                                                </button>

                                                            </form>

                                                        </div>

                                                    </div>

                                                </td>

                                            </tr>

                                        @empty

                                            <tr>

                                                <td colspan="9" class="text-center py-5">

                                                    <div class="text-muted">

                                                        No categories found.

                                                    </div>

                                                </td>

                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Category Offcanvas -->
        <x-offcanvas id="categoriesCanvas" title="Category" formId="categoryForm">

            <form id="categoryForm" class="ajax-form" method="POST" action="{{ route('products.categories.store') }}">

                @csrf

                @php

                    $config = [
                        [
                            'name' => 'project_id',
                            'label' => 'Project',
                            'type' => 'select',
                            'options' => $projects ?? [],
                            'required' => true,
                            'col' => 6,
                        ],

                        [
                            'name' => 'name',
                            'label' => 'Category Name',
                            'type' => 'text',
                            'placeholder' => 'Enter category name',
                            'required' => true,
                            'col' => 6,
                        ],

                        [
                            'name' => 'selection_type',
                            'label' => 'Selection Type',
                            'type' => 'select',
                            'options' => [
                                'single' => 'Single',
                                'multiple' => 'Multiple',
                            ],
                            'required' => true,
                            'col' => 6,
                        ],

                        [
                            'name' => 'sort_order',
                            'label' => 'Sort Order',
                            'type' => 'number',
                            'placeholder' => 'Enter sort order',
                            'col' => 6,
                        ],

                        [
                            'name' => 'description',
                            'label' => 'Description',
                            'type' => 'textarea',
                            'placeholder' => 'Enter category description',
                            'col' => 12,
                        ],

                        [
                            'name' => 'is_required',
                            'label' => 'Required Category',
                            'type' => 'checkbox',
                            'col' => 4,
                        ],

                        [
                            'name' => 'has_tabs',
                            'label' => 'Enable Tabs',
                            'type' => 'checkbox',
                            'col' => 4,
                        ],

                        [
                            'name' => 'has_default',
                            'label' => 'Default Selection',
                            'type' => 'checkbox',
                            'col' => 4,
                        ],

                        [
                            'name' => 'active',
                            'label' => 'Active',
                            'type' => 'checkbox',
                            'checked' => true,
                            'col' => 12,
                        ],
                    ];

                @endphp

                <x-form.fields :config="$config" />

            </form>

        </x-offcanvas>
    </div>
@endsection
