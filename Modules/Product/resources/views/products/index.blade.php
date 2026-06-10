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
                                    Products
                                    <span class="count-title">
                                        {{ $products->total() }}
                                    </span>
                                </h4>

                            </div>

                            <div class="col-6 text-end">

                                <div class="head-icons">

                                    <a href="{{ route('products.index') }}" data-bs-toggle="tooltip" title="Refresh">

                                        <i class="ti ti-refresh-dot"></i>

                                    </a>

                                    <a href="javascript:void(0);" id="collapse-header">

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

                                    <form method="GET" action="{{ route('products.index') }}">

                                        <div class="icon-form">

                                            <span class="form-icon">
                                                <i class="ti ti-search"></i>
                                            </span>

                                            <input type="text" name="search" class="form-control"
                                                placeholder="Search Products" value="{{ request('search') }}">

                                        </div>

                                    </form>

                                </div>

                                <!-- Add Product -->
                                <div class="col-sm-8">

                                    <div class="d-flex gap-2 justify-content-sm-end mt-3 mt-sm-0">
                                        @if (userCan('products.create'))
                                            <a href="{{ route('products.import') }}" class="btn btn-info">

                                                <i class="ti ti-download me-2"></i>

                                                Import Product

                                            </a>
                                            <a href="{{ route('products.create') }}" class="btn btn-primary">

                                                <i class="ti ti-square-rounded-plus me-2"></i>

                                                Add Product

                                            </a>
                                        @endif
                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="card-body">

                            <!-- Product Table -->
                            <div class="table-responsive">

                                <table class="table text-nowrap">

                                    <thead>

                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>

                                            <th>CRM Product Id</th>

                                            <th>Cost Price</th>

                                            <th>Selling Price</th>

                                            <th>Product Code</th>

                                            <th>Status</th>

                                            <th class="text-end">
                                                Actions
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody id="product-list">

                                        @include('product::products.partials.list', [
                                            'products' => $products,
                                        ])
                                    </tbody>

                                </table>

                            </div>
                            <!-- /Product Table -->

                            <!-- Pagination -->
                            <div class="mt-3">

                                {{ $products->links() }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Add Product Offcanvas -->
    {{-- <x-offcanvas id="productCanvas" title="Add Product" formId="productForm">

        <form id="productForm" class="ajax-form" method="POST" action="{{ route('products.store') }}">

            @csrf

            @php

                $config = [
                    [
                        'name' => 'name',
                        'label' => 'Product Name',
                        'type' => 'text',
                        'placeholder' => 'Enter product name',
                        'required' => true,
                        'col' => 6,
                    ],
                    [
                        'name' => 'cost',
                        'label' => 'Cost Price',
                        'type' => 'number',
                        'placeholder' => 'Enter cost price',
                        'col' => 6,
                    ],
                    [
                        'name' => 'price',
                        'label' => 'Selling Price',
                        'type' => 'number',
                        'placeholder' => 'Enter selling price',
                        'required' => true,
                        'col' => 6,
                    ],

                    [
                        'name' => 'pipedrive_product_id',
                        'label' => 'Pipedrive ID',
                        'type' => 'text',
                        'placeholder' => 'Enter pipedrive ID',
                        'col' => 6,
                    ],
                    [
                        'name' => 'product_code',
                        'label' => 'Product Code',
                        'type' => 'text',
                        'placeholder' => 'Enter product code',
                        'col' => 6,
                    ],

                    [
                        'name' => 'currency_code',
                        'label' => 'Currency Code',
                        'type' => 'text',
                        'value' => $currency_code,
                        'placeholder' => 'Select currency',
                        'disabled' => true,
                        'col' => 6,
                    ],
                    [
                        'name' => 'description',
                        'label' => 'Description',
                        'type' => 'textarea',
                        'placeholder' => 'Enter description',
                        'col' => 12,
                    ],
                    [
                        'name' => 'is_default',
                        'label' => 'Default Product',
                        'type' => 'checkbox',
                        'col' => 3,
                    ],

                    [
                        'name' => 'is_pro',
                        'label' => 'Pro Product',
                        'type' => 'checkbox',
                        'col' => 3,
                    ],

                    [
                        'name' => 'show_only',
                        'label' => 'Show Only',
                        'type' => 'checkbox',
                        'col' => 3,
                    ],

                    [
                        'name' => 'active',
                        'label' => 'Active',
                        'type' => 'checkbox',
                        'checked' => true,
                        'col' => 3,
                    ],
                ];

            @endphp

            <x-form.fields :config="$config" />

        </form>

    </x-offcanvas> --}}
@endsection
