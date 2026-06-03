@extends('layouts.app')

@section('content')
    <div class="page-wrapper">

        <div class="content">

            <!-- Page Header -->
            <div class="page-header">

                <div class="row align-items-center">

                    <div class="col-md-6">

                        <h4 class="page-title">

                            Product
                            <span class="count-title">
                                create
                            </span>
                        </h4>

                    </div>
                    <div class="col-6 text-end">

                        <div class="head-icons">
                            <a href="{{ route('products.index') }}" class="btn btn-light">

                                <i class="ti ti-arrow-left me-1"></i>

                            </a>
                            <a href="{{ route('products.create') }}" data-bs-toggle="tooltip" title="Refresh">

                                <i class="ti ti-refresh-dot"></i>

                            </a>

                            <a href="javascript:void(0);" id="collapse-header">

                                <i class="ti ti-chevrons-up"></i>

                            </a>

                        </div>

                    </div>


                </div>

            </div>

            <!-- Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 fw-semibold">Add Product</h5>
                </div>
                <div class="card-body">

                    <form method="POST" action="{{ route('products.store') }}">
                        @csrf

                        <!-- Product Information -->
                        <div class="border rounded p-3 mb-4">
                            <h5 class="mb-3">
                                <i class="ti ti-package me-1"></i>
                                Product Information
                            </h5>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">
                                        CRM Product ID
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="crm_product_id" class="form-control"
                                        placeholder="Enter CRM product ID" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Scrap Categories
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select class="js-example-placeholder-multiple select2 js-states"
                                        name="scrap_categories[]" multiple required>

                                        @foreach ($scrapCategories as $id => $category)
                                            <option value="{{ $id }}">
                                                {{ $category }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Product Title
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="title" class="form-control"
                                        placeholder="Enter product title" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Sub Title
                                    </label>
                                    <input type="text" name="sub_title" class="form-control"
                                        placeholder="Enter sub title">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Product Code
                                    </label>
                                    <input type="text" name="product_code" class="form-control"
                                        placeholder="Enter product code">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"></label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" name="is_best_seller"
                                            value="1">

                                        <label class="form-check-label">
                                            Best Seller Product
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Pricing Information -->
                        <div class="border rounded p-3 mb-4">
                            <h5 class="mb-3">
                                <i class="ti ti-currency-dollar me-1"></i>
                                Pricing Information
                            </h5>

                            <div class="row g-3">

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Cost Price
                                    </label>
                                    <input type="number" name="cost" class="form-control"
                                        placeholder="Enter cost price">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Selling Price
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="price" class="form-control"
                                        placeholder="Enter selling price" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Currency Code
                                    </label>
                                    <input type="text" name="currency_code" class="form-control"
                                        value="{{ active_currency_code() ?? '' }}" disabled>
                                </div>

                            </div>
                        </div>

                        <!-- Product Description -->
                        <div class="border rounded p-3 mb-4">
                            <h5 class="mb-3">
                                <i class="ti ti-file-description me-1"></i>
                                Product Description
                            </h5>

                            <div class="row">

                                <div class="col-md-12">
                                    <label class="form-label">
                                        Description
                                    </label>
                                    <textarea name="description" class="form-control ckeditor" rows="5" placeholder="Enter product description"></textarea>
                                </div>

                            </div>
                        </div>

                        <!-- Proposal Information -->
                        <div class="border rounded p-3 mb-4">
                            <h5 class="mb-3">
                                <i class="ti ti-file-text me-1"></i>
                                Proposal Information
                            </h5>

                            <div class="row">

                                <div class="col-md-12">
                                    <label class="form-label">
                                        Proposal Description
                                    </label>
                                    <textarea name="proposal_desc" class="form-control ckeditor" rows="5" placeholder="Enter proposal description"></textarea>
                                </div>

                            </div>
                        </div>


                        <!-- Actions -->
                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('products.index') }}" class="btn btn-light">
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i>
                                Create Product
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>
@endsection
