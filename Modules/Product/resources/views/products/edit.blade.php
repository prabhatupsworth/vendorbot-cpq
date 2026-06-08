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
                                Edit
                            </span>
                        </h4>

                    </div>

                    <div class="col-6 text-end">

                        <div class="head-icons">

                            <a href="{{ route('products.index') }}" class="btn btn-light">
                                <i class="ti ti-arrow-left me-1"></i>
                            </a>

                            <a href="{{ route('products.edit', $product->id) }}" data-bs-toggle="tooltip" title="Refresh">

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
                    <h5 class="mb-0 fw-semibold">
                        Edit Product
                    </h5>
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route('products.update', $product->id) }}">

                        @csrf
                        @method('PUT')

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
                                    </label>

                                    <input type="text" name="crm_product_id"
                                        class="form-control @error('crm_product_id') is-invalid @enderror"
                                        value="{{ old('crm_product_id', $product->crm_product_id) }}"
                                        placeholder="Enter CRM product ID">
                                    @error('crm_product_id')
                                        <div class="invalid-feedback">

                                            {{ $message }}

                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Scrap Categories
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select
                                        class="js-example-placeholder-multiple select2 js-states @error('scrap_categories') is-invalid @enderror"
                                        name="scrap_categories[]" multiple required>

                                        @foreach ($scrapCategories as $id => $category)
                                            <option value="{{ $id }}"
                                                {{ in_array($id, old('scrap_categories', $product->scrapCategories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                {{ $category }}
                                            </option>
                                        @endforeach

                                    </select>

                                    @error('scrap_categories')
                                        <div class="invalid-feedback">

                                            {{ $message }}

                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Product Title
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title', $product->title) }}" placeholder="Enter product title"
                                        required>

                                    @error('title')
                                        <div class="invalid-feedback">

                                            {{ $message }}

                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Sub Title
                                    </label>

                                    <input type="text" name="sub_title"
                                        class="form-control @error('sub_title') is-invalid @enderror"
                                        value="{{ old('sub_title', $product->sub_title) }}" placeholder="Enter sub title">
                                    @error('sub_title')
                                        <div class="invalid-feedback">

                                            {{ $message }}

                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Product Code
                                    </label>

                                    <input type="text" name="product_code"
                                        class="form-control @error('product_code') is-invalid @enderror"
                                        value="{{ old('product_code', $product->product_code) }}"
                                        placeholder="Enter product code">
                                    @error('product_code')
                                        <div class="invalid-feedback">

                                            {{ $message }}

                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">

                                    <label class="form-label d-block">
                                        Product Status
                                    </label>

                                    <div class="form-check form-switch mt-2">

                                        <input class="form-check-input @error('is_best_seller') is-invalid @enderror"
                                            type="checkbox" name="is_best_seller" value="1"
                                            {{ old('is_best_seller', $product->is_best_seller) ? 'checked' : '' }}>

                                        <label class="form-check-label">
                                            Best Seller Product
                                        </label>
                                        @error('is_best_seller')
                                            <div class="invalid-feedback">

                                                {{ $message }}

                                            </div>
                                        @enderror


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

                                    <input type="number" step="0.01" name="cost"
                                        class="form-control @error('cost') is-invalid @enderror"
                                        value="{{ old('cost', $product->cost) }}" placeholder="Enter cost price">
                                    @error('cost')
                                        <div class="invalid-feedback">

                                            {{ $message }}

                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Selling Price
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="number" step="0.01" name="price"
                                        class="form-control @error('price') is-invalid @enderror"
                                        value="{{ old('price', $product->price) }}" placeholder="Enter selling price"
                                        required>
                                    @error('price')
                                        <div class="invalid-feedback">

                                            {{ $message }}

                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Currency Code
                                    </label>

                                    <input type="text" class="form-control" value="{{ $product->currency_code }}"
                                        disabled>
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

                                    <textarea name="description" class="form-control ckeditor @error('description') is-invalid @enderror" rows="5"
                                        placeholder="Enter product description">{!! old('description', $product->description ?? '') !!}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">

                                            {{ $message }}

                                        </div>
                                    @enderror
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

                                    <textarea name="proposal_desc" class="form-control ckeditor @error('description') is-invalid @enderror"
                                        rows="5" placeholder="Enter proposal description">{!! old('proposal_desc', $product->proposal_desc ?? '') !!}</textarea>
                                    @error('proposal_desc')
                                        <div class="invalid-feedback">

                                            {{ $message }}

                                        </div>
                                    @enderror

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

                                Update Product

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>
@endsection
