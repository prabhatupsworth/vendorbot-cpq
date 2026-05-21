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

                            <div class="col-6">

                                <h4 class="page-title">
                                    Category Tabs
                                    <span class="count-title">
                                        {{ $tabs->total() }}
                                    </span>
                                </h4>

                            </div>

                            <div class="col-6 text-end">

                                <div class="head-icons">

                                    <a href="{{ route('products.tabs.index') }}"
                                        data-bs-toggle="tooltip"
                                        title="Refresh">

                                        <i class="ti ti-refresh-dot"></i>

                                    </a>

                                    <a href="javascript:void(0);"
                                        id="collapse-header">

                                        <i class="ti ti-chevrons-up"></i>

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>
                    <!-- /Page Header -->

                    <div class="card">

                        <div class="card-header">

                            <div class="row align-items-center">

                                <!-- Search -->
                                <div class="col-sm-4">

                                    <form method="GET"
                                        action="{{ route('products.tabs.index') }}">

                                        <div class="icon-form">

                                            <span class="form-icon">
                                                <i class="ti ti-search"></i>
                                            </span>

                                            <input type="text"
                                                name="search"
                                                class="form-control"
                                                placeholder="Search Tabs"
                                                value="{{ request('search') }}">

                                        </div>

                                    </form>

                                </div>

                                <!-- Add Button -->
                                <div class="col-sm-8">

                                    <div class="d-flex justify-content-sm-end mt-3 mt-sm-0">

                                        <a href="javascript:void(0);"
                                            class="btn btn-primary"
                                            data-bs-toggle="offcanvas"
                                            data-bs-target="#categoryTabsCanvas">

                                            <i class="ti ti-square-rounded-plus me-2"></i>

                                            Add Tab

                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="card-body">

                            <!-- Tabs Table -->
                            <div class="table-responsive">

                                <table class="table table-hover mb-0">

                                    <thead>

                                        <tr>

                                            <th>#</th>

                                            <th>Tab Name</th>

                                            <th>Category</th>

                                            <th>Description</th>

                                            <th>Default</th>

                                            <th>Status</th>

                                            <th>Sort Order</th>

                                            <th>Created At</th>

                                            <th class="text-end">
                                                Actions
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        @forelse($tabs as $tab)

                                            <tr>

                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>

                                                <td>

                                                    <div class="fw-semibold">

                                                        {{ $tab->name }}

                                                    </div>

                                                </td>

                                                <td>

                                                    {{ $tab->category?->name ?? '-' }}

                                                </td>

                                                <td>

                                                    {{ \Illuminate\Support\Str::limit($tab->description, 50) }}

                                                </td>

                                                <td>

                                                    @if($tab->is_default)

                                                        <span class="badge badge-soft-success">

                                                            Yes

                                                        </span>

                                                    @else

                                                        <span class="badge badge-soft-secondary">

                                                            No

                                                        </span>

                                                    @endif

                                                </td>

                                                <td>

                                                    @if($tab->active)

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

                                                    {{ $tab->sort_order }}

                                                </td>

                                                <td>

                                                    {{ $tab->created_at?->format('d M Y') }}

                                                </td>

                                                <td>

                                                    <div class="dropdown table-action">

                                                        <a href="javascript:void(0);"
                                                            class="action-icon"
                                                            data-bs-toggle="dropdown">

                                                            <i class="fa fa-ellipsis-v"></i>

                                                        </a>

                                                        <div class="dropdown-menu dropdown-menu-end">

                                                            <!-- Edit -->
                                                            <a href="javascript:void(0);"
                                                                class="dropdown-item edit-tab-btn"
                                                                data-id="{{ $tab->id }}">

                                                                <i class="ti ti-edit text-blue"></i>

                                                                Edit

                                                            </a>

                                                            <!-- Delete -->
                                                            <form method="POST"
                                                                action="{{ route('products.tabs.destroy', $tab->id) }}">

                                                                @csrf
                                                                @method('DELETE')

                                                                <button type="submit"
                                                                    class="dropdown-item confirm-delete">

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

                                                <td colspan="9"
                                                    class="text-center py-5">

                                                    <div class="text-muted">

                                                        No tabs found.

                                                    </div>

                                                </td>

                                            </tr>

                                        @endforelse

                                    </tbody>

                                </table>

                            </div>
                            <!-- /Tabs Table -->

                            <!-- Pagination -->
                            <div class="mt-3">

                                {{ $tabs->links() }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Add Category Tab Offcanvas -->
    <x-offcanvas id="categoryTabsCanvas"
        title="Category Tab"
        formId="categoryTabForm">

        <form id="categoryTabForm"
            class="ajax-form"
            method="POST"
            action="{{ route('products.tabs.store') }}">

            @csrf

            @php

                $config = [

                    [
                        'name' => 'category_id',
                        'label' => 'Category',
                        'type' => 'select',
                        'options' => $categories ?? [],
                        'required' => true,
                        'col' => 6,
                    ],

                    [
                        'name' => 'name',
                        'label' => 'Tab Name',
                        'type' => 'text',
                        'placeholder' => 'Enter tab name',
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
                        'name' => 'is_default',
                        'label' => 'Default Tab',
                        'type' => 'checkbox',
                        'col' => 6,
                    ],

                    [
                        'name' => 'active',
                        'label' => 'Active',
                        'type' => 'checkbox',
                        'checked' => true,
                        'col' => 6,
                    ],

                ];

            @endphp

            <x-form.fields :config="$config" />

        </form>

    </x-offcanvas>

@endsection
