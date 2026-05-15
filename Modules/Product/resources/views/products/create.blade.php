@extends('layouts.app')

@section('content')
    <div class="page-wrapper">

        <div class="content">

            <!-- Page Header -->
            <div class="page-header">

                <div class="row align-items-center">

                    <div class="col-md-6">

                        <h4 class="page-title">

                            Import Product

                        </h4>

                    </div>

                    <div class="col-md-6 text-end">

                        <a href="{{ route('products.index') }}" class="btn btn-light">

                            <i class="ti ti-arrow-left me-1"></i>

                            Back

                        </a>

                    </div>

                </div>

            </div>

            <!-- Card -->
            <div class="card">

                <div class="card-body">

                    <form id="productForm" class="ajax-form" method="POST" action="{{ route('products.store') }}">

                        @csrf

                        <div class="row">

                            <!-- Pipedrive Product ID -->
                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Pipedrive Product ID
                                        <span class="text-danger">*</span>

                                    </label>

                                    <input type="text" name="pipedrive_product_id" class="form-control"
                                        placeholder="Enter pipedrive product ID">

                                </div>

                            </div>

                            <!-- Scrap Categories -->
                            <div class="col-md-6">

                                <div class="mb-3">
                                     <label class="form-label">
                                        Scrap Categories
                                        <span class="text-danger">*</span>

                                    </label>
                                    <select class="js-example-placeholder-multiple select2 js-states" id="scrap_categories"
                                        name="scrap_categories[]" multiple>

                                        @foreach ($scrapCategories as $id => $category)
                                            <option value="{{ $id }}">

                                                {{ $category }}

                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="col-md-12 mt-2">

                                <button type="submit" class="btn btn-primary">

                                    <i class="ti ti-download me-1"></i>

                                    Import Product

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>
@endsection
