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

                                    <div class="d-flex justify-content-sm-end mt-3 mt-sm-0">

                                        <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="offcanvas"
                                            data-bs-target="#productCanvas">

                                            <i class="ti ti-square-rounded-plus me-2"></i>

                                            Add Product

                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="card-body">

                            <!-- Product Table -->
                            <div class="table-responsive">

                                <table class="table table-hover mb-0">

                                    <thead>

                                        <tr>

                                            <th>Product</th>

                                            <th>Project</th>

                                            <th>Price</th>

                                            <th>Discount</th>

                                            <th>Final Price</th>

                                            <th>Status</th>

                                            <th class="text-end">
                                                Actions
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody id="product-list">

                                        @forelse($products as $product)
                                            @include('product::products.partials.list', [
                                                'product' => $product,
                                            ])

                                        @empty

                                            <tr>

                                                <td colspan="10" class="text-center py-5">

                                                    <div class="text-muted">

                                                        No products found.

                                                    </div>

                                                </td>

                                            </tr>
                                        @endforelse

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
    <x-offcanvas id="productCanvas" title="Add Product" formId="productForm">

        <form id="productForm" class="ajax-form" method="POST" action="{{ route('products.store') }}">

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
                        'label' => 'Product Name',
                        'type' => 'text',
                        'placeholder' => 'Enter product name',
                        'required' => true,
                        'col' => 6,
                    ],

                    [
                        'name' => 'price',
                        'label' => 'Price',
                        'type' => 'number',
                        'placeholder' => 'Enter price',
                        'required' => true,
                        'col' => 6,
                    ],

                    [
                        'name' => 'cost',
                        'label' => 'Cost',
                        'type' => 'number',
                        'placeholder' => 'Enter cost',
                        'col' => 6,
                    ],

                    [
                        'name' => 'discount_type',
                        'label' => 'Discount Type',
                        'type' => 'select',
                        'options' => [
                            'fixed' => 'Fixed',
                            'percent' => 'Percent',
                        ],
                        'col' => 6,
                    ],

                    [
                        'name' => 'discount_value',
                        'label' => 'Discount Value',
                        'type' => 'number',
                        'placeholder' => 'Enter discount value',
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

    </x-offcanvas>
@endsection
