@extends('layouts.app')

@section('content')
    <div class="page-wrapper">

        <div class="content">

            {{-- PAGE HEADER --}}

            <div class="page-header mb-4">

                <div class="row align-items-center">

                    <div class="col-lg-6">

                        <h3 class="page-title fw-bold">

                            Create Coupon

                        </h3>

                        <p class="text-muted mb-0">

                            Create and manage discount coupons

                        </p>

                    </div>

                    <div class="col-lg-6 text-end">

                        <a href="{{ route('coupon.index') }}" class="btn btn-light">

                            <i class="ti ti-arrow-left me-1"></i>

                            Back

                        </a>

                    </div>

                </div>

            </div>

            {{-- FORM CARD --}}

            <div class="card border-0 shadow-sm">

                <form action="{{ route('coupon.store') }}" method="POST">

                    @csrf

                    <div class="card-body">

                        <div class="row">

                            {{-- PROJECT --}}

                            <div class="col-md-6">

                                <div class="mb-4">

                                    <label class="form-label">
                                        Project
                                    </label>

                                    <select name="project_id"
                                        class="select @error('project_id') is-invalid @enderror">

                                        <option value="">
                                            Select Project
                                        </option>

                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}"
                                                {{ old('project_id') == $project->id ? 'selected' : '' }}>

                                                {{ $project->name }}

                                            </option>
                                        @endforeach

                                    </select>

                                    @error('project_id')
                                        <div class="invalid-feedback">

                                            {{ $message }}

                                        </div>
                                    @enderror

                                </div>

                            </div>

                            {{-- NAME --}}

                            <div class="col-md-6">

                                <div class="mb-4">

                                    <label class="form-label">
                                        Coupon Name
                                    </label>

                                    <input type="text" name="name" id="couponName"
                                        class="form-control @error('name') is-invalid @enderror" placeholder="Summer Sale"
                                        value="{{ old('name') }}">

                                    @error('name')
                                        <div class="invalid-feedback">

                                            {{ $message }}

                                        </div>
                                    @enderror

                                </div>

                            </div>

                            {{-- CODE --}}

                            <div class="col-md-6">

                                <div class="mb-4">

                                    <label class="form-label">
                                        Coupon Code
                                    </label>

                                    <div class="input-group">

                                        <input type="text" name="code" id="couponCode"
                                            class="form-control @error('code') is-invalid @enderror"
                                            placeholder="SUMMER-8X2K" value="{{ old('code') }}">

                                        <button type="button" class="btn btn-primary" id="generateCouponBtn">

                                            Generate

                                        </button>

                                    </div>

                                    @error('code')
                                        <div class="invalid-feedback d-block">

                                            {{ $message }}

                                        </div>
                                    @enderror

                                </div>

                            </div>

                            {{-- TYPE --}}

                            <div class="col-md-6">

                                <div class="mb-4">

                                    <label class="form-label">
                                        Discount Type
                                    </label>

                                    <select name="type" class="select @error('type') is-invalid @enderror">

                                        <option value="fixed">
                                            Fixed
                                        </option>

                                        <option value="percentage">
                                            Percentage
                                        </option>

                                    </select>

                                    @error('type')
                                        <div class="invalid-feedback">

                                            {{ $message }}

                                        </div>
                                    @enderror

                                </div>

                            </div>

                            {{-- AMOUNT --}}

                            <div class="col-md-4">

                                <div class="mb-4">

                                    <label class="form-label">
                                        Discount Amount
                                    </label>

                                    <input type="number" step="0.01" name="amount"
                                        class="form-control @error('amount') is-invalid @enderror" placeholder="10"
                                        value="{{ old('amount') }}">

                                    @error('amount')
                                        <div class="invalid-feedback">

                                            {{ $message }}

                                        </div>
                                    @enderror

                                </div>

                            </div>

                            {{-- MIN ORDER VALUE --}}

                            <div class="col-md-4">

                                <div class="mb-4">

                                    <label class="form-label">
                                        Min Order Value
                                    </label>

                                    <input type="number" step="0.01" name="min_order_value" class="form-control"
                                        placeholder="100" value="{{ old('min_order_value') }}">

                                </div>

                            </div>

                            {{-- USAGE LIMIT --}}

                            <div class="col-md-4">

                                <div class="mb-4">

                                    <label class="form-label">
                                        Usage Limit
                                    </label>

                                    <input type="number" name="usage_limit" class="form-control" placeholder="100"
                                        value="{{ old('usage_limit') }}">

                                </div>

                            </div>

                            {{-- VALID FROM --}}

                            <div class="col-md-6">

                                <div class="mb-4">

                                    <label class="form-label">
                                        Valid From
                                    </label>

                                    <input type="datetime-local" name="valid_from" class="form-control"
                                        value="{{ old('valid_from') }}">

                                </div>

                            </div>

                            {{-- VALID UNTIL --}}

                            <div class="col-md-6">

                                <div class="mb-4">

                                    <label class="form-label">
                                        Valid Until
                                    </label>

                                    <input type="datetime-local" name="valid_until" class="form-control"
                                        value="{{ old('valid_until') }}">

                                </div>

                            </div>

                            {{-- PER PERSON --}}

                            <div class="col-md-6">

                                <div class="mb-4">

                                    <div class="form-check form-switch">

                                        <input class="form-check-input" type="checkbox" name="per_person" value="1"
                                            id="perPerson">

                                        <label class="form-check-label" for="perPerson">

                                            Per Person Coupon

                                        </label>

                                    </div>
                                    <small class="text-muted d-block mt-2">

                                        Enable this option to allow only one
                                        coupon usage per customer.

                                    </small>
                                </div>

                            </div>

                            {{-- STATUS --}}

                            <div class="col-md-6">

                                <div class="mb-4">

                                    <div class="form-check form-switch">

                                        <input class="form-check-input" type="checkbox" name="status" value="1"
                                            id="status" checked>

                                        <label class="form-check-label" for="status">

                                            Active

                                        </label>

                                    </div>

                                </div>

                            </div>

                            {{-- DESCRIPTION --}}

                            <div class="col-md-12">

                                <div class="mb-4">

                                    <label class="form-label">
                                        Description
                                    </label>

                                    <textarea name="description" rows="5" class="form-control" placeholder="Coupon description...">{{ old('description') }}</textarea>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- FOOTER --}}

                    <div class="card-footer bg-white">

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('coupon.index') }}" class="btn btn-light">

                                Cancel

                            </a>

                            <button type="submit" class="btn btn-primary">

                                <i class="ti ti-device-floppy me-1"></i>

                                Save Coupon

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>
    @push('scripts')
        <script>
            document.getElementById('generateCouponBtn').addEventListener('click', function() {
                const code = generateCouponCode(10);
                document.getElementById('couponCode').value = code;
            });

            function generateCouponCode(length) {
                const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                let result = '';
                for (let i = 0; i < length; i++) {
                    result += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                return result;
            }
        </script>
    @endpush
@endsection
