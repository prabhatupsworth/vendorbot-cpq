@extends('layouts.app')

@section('content')

    <div class="page-wrapper">

        <div class="content">

            {{-- PAGE HEADER --}}

            <div class="page-header mb-4">

                <div class="row align-items-center">

                    <div class="col-lg-6">

                        <h3 class="page-title fw-bold">

                            Coupon Details

                        </h3>

                        <p class="text-muted mb-0">

                            View complete coupon information

                        </p>

                    </div>

                    <div class="col-lg-6 text-end">

                        <div
                            class="d-flex justify-content-end gap-2"
                        >

                            {{-- EDIT --}}

                            <a
                                href="{{ route('coupon.edit', $coupon->id) }}"
                                class="btn btn-primary"
                            >

                                <i class="ti ti-edit me-1"></i>

                                Edit

                            </a>

                            {{-- BACK --}}

                            <a
                                href="{{ route('coupon.index') }}"
                                class="btn btn-light"
                            >

                                <i class="ti ti-arrow-left me-1"></i>

                                Back

                            </a>

                        </div>

                    </div>

                </div>

            </div>

            <div class="row">

                {{-- LEFT SIDE --}}

                <div class="col-xl-8">

                    {{-- BASIC INFO --}}

                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white border-0">

                            <h5 class="mb-0 fw-semibold">

                                Basic Information

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                {{-- NAME --}}

                                <div class="col-md-6 mb-4">

                                    <label class="text-muted small">
                                        Coupon Name
                                    </label>

                                    <h5 class="fw-semibold mb-0">

                                        {{ $coupon->name }}

                                    </h5>

                                </div>

                                {{-- CODE --}}

                                <div class="col-md-6 mb-4">

                                    <label class="text-muted small">
                                        Coupon Code
                                    </label>

                                    <div>

                                        <code
                                            class="bg-light"
                                        >

                                            {{ $coupon->code }}

                                        </code>

                                    </div>

                                </div>



                                {{-- TYPE --}}

                                <div class="col-md-6 mb-4">

                                    <label class="text-muted small">
                                        Discount Type
                                    </label>

                                    <div>

                                        @if ($coupon->type == 'amount')

                                            <span
                                                class="badge bg-outline-danger"
                                            >

                                                Amount

                                            </span>

                                        @else

                                            <span
                                                class="badge bg-outline-danger"
                                            >

                                                Percentage

                                            </span>

                                        @endif

                                    </div>

                                </div>

                                {{-- AMOUNT --}}

                                <div class="col-md-6 mb-4">

                                    <label class="text-muted small">
                                        Discount
                                    </label>

                                    <h4
                                        class="fw-bold text-primary mb-0"
                                    >

                                        @if ($coupon->type == 'amount')

                                         {{ currency($coupon->amount) }}


                                        @else

                                         {{ active_currency_symbol() }} {{ number_format($coupon->amount, 0) }}%

                                        @endif

                                    </h4>

                                </div>

                                {{-- MIN ORDER VALUE --}}

                                <div class="col-md-6 mb-4">

                                    <label class="text-muted small">
                                        Minimum Order Value
                                    </label>

                                    <h6 class="mb-0">

                                        @if ($coupon->min_order_value)


                                            {{ currency($coupon->min_order_value) }}

                                        @else

                                            -

                                        @endif

                                    </h6>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- VALIDITY --}}

                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white border-0">

                            <h5 class="mb-0 fw-semibold">

                                Validity & Usage

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                {{-- VALID FROM --}}

                                <div class="col-md-6 mb-4">

                                    <label class="text-muted small">
                                        Valid From
                                    </label>

                                    <h6 class="mb-0">

                                        {{ $coupon->valid_from ? $coupon->valid_from->format('d M Y h:i A') : '-' }}

                                    </h6>

                                </div>

                                {{-- VALID UNTIL --}}

                                <div class="col-md-6 mb-4">

                                    <label class="text-muted small">
                                        Valid Until
                                    </label>

                                    <h6 class="mb-0">

                                        {{ $coupon->valid_until ? $coupon->valid_until->format('d M Y h:i A') : '-' }}

                                    </h6>

                                </div>

                                {{-- USAGE LIMIT --}}

                                <div class="col-md-6 mb-4">

                                    <label class="text-muted small">
                                        Usage Limit
                                    </label>

                                    <h6 class="mb-0">

                                        {{ $coupon->usage_limit ?: 'Unlimited' }}

                                    </h6>

                                </div>

                                {{-- USED COUNT --}}

                                <div class="col-md-6 mb-4">

                                    <label class="text-muted small">
                                        Used Count
                                    </label>

                                    <h6 class="mb-0">

                                        {{ $coupon->used_count }}

                                    </h6>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- DESCRIPTION --}}

                    <div class="card border-0 shadow-sm">

                        <div class="card-header bg-white border-0">

                            <h5 class="mb-0 fw-semibold">

                                Description

                            </h5>

                        </div>

                        <div class="card-body">

                            @if ($coupon->description)

                                <p class="mb-0">

                                    {{ $coupon->description }}

                                </p>

                            @else

                                <p class="text-muted mb-0">

                                    No description available.

                                </p>

                            @endif

                        </div>

                    </div>

                </div>

                {{-- RIGHT SIDE --}}

                <div class="col-xl-4">

                    {{-- STATUS CARD --}}

                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-body text-center">

                            <div
                                class="
                                    rounded-circle
                                    mx-auto
                                    d-flex
                                    align-items-center
                                    justify-content-center
                                    mb-3

                                    {{ $coupon->status
                                        ? 'bg-success-subtle text-success'
                                        : 'bg-danger-subtle text-danger'
                                    }}
                                "
                                style="
                                    width:90px;
                                    height:90px;
                                "
                            >

                                <i
                                    class="
                                        ti

                                        {{ $coupon->status
                                            ? 'ti-circle-check'
                                            : 'ti-circle-x'
                                        }}

                                        fs-1
                                    "
                                ></i>

                            </div>

                            <h4 class="fw-bold">

                                {{ $coupon->status ? 'Active' : 'Inactive' }}

                            </h4>

                            <p class="text-muted mb-0">

                                Coupon Status

                            </p>

                        </div>

                    </div>

                    {{-- QUICK STATS --}}

                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white border-0">

                            <h5 class="mb-0 fw-semibold">

                                Quick Stats

                            </h5>

                        </div>

                        <div class="card-body">

                            <div
                                class="d-flex justify-content-between mb-3"
                            >

                                <span class="text-muted">
                                    Per Person
                                </span>

                                <strong>

                                    {{ $coupon->per_person ? 'Yes' : 'No' }}

                                </strong>

                            </div>

                            <div
                                class="d-flex justify-content-between mb-3"
                            >

                                <span class="text-muted">
                                    Created At
                                </span>

                                <strong>

                                    {{ $coupon->created_at->format('d M Y') }}

                                </strong>

                            </div>

                            <div
                                class="d-flex justify-content-between"
                            >

                                <span class="text-muted">
                                    Last Updated
                                </span>

                                <strong>

                                    {{ $coupon->updated_at->format('d M Y') }}

                                </strong>

                            </div>

                        </div>

                    </div>

                    {{-- ACTIONS --}}

                    <div class="card border-0 shadow-sm">

                        <div class="card-body">

                            <div class="d-grid gap-2">

                                {{-- EDIT --}}

                                <a
                                    href="{{ route('coupon.edit', $coupon->id) }}"
                                    class="btn btn-primary"
                                >

                                    <i class="ti ti-edit me-1"></i>

                                    Edit Coupon

                                </a>

                                {{-- DELETE --}}

                                <form
                                    action="{{ route('coupon.destroy', $coupon->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this coupon?')"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger w-100"
                                    >

                                        <i class="ti ti-trash me-1"></i>

                                        Delete Coupon

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
