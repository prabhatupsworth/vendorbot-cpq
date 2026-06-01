@extends('layouts.app')

@section('content')
    <div class="page-wrapper">

        <div class="content">

            {{-- PAGE HEADER --}}

            <div class="page-header mb-4">

                <div class="row align-items-center">

                    <div class="col-lg-6">

                        <h3 class="page-title fw-bold">

                            Coupons

                            <span class="badge bg-primary ms-2">

                                {{ $coupons->total() }}

                            </span>

                        </h3>

                        <p class="text-muted mb-0">

                            Manage all coupons and discount codes

                        </p>

                    </div>

                    <div class="col-lg-6 text-end">
                        @can('coupons.create')
                        <a href="{{ route('coupon.create') }}" class="btn btn-primary">

                            <i class="ti ti-plus me-1"></i>

                            Create Coupon

                        </a>
                        @endcan
                    </div>

                </div>

            </div>

            {{-- STATS CARDS --}}

            <div class="row">

                {{-- TOTAL --}}

                <div class="col-xl-3 col-md-6">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <p class="text-muted mb-1">
                                        Total Coupons
                                    </p>

                                    <h3 class="fw-bold mb-0">

                                        {{ $coupons->total() }}

                                    </h3>

                                </div>

                                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center"
                                    style="
                                        width:60px;
                                        height:60px;
                                    ">

                                    <i class="ti ti-ticket fs-3"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- ACTIVE --}}

                <div class="col-xl-3 col-md-6">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <p class="text-muted mb-1">
                                        Active
                                    </p>

                                    <h3 class="fw-bold text-success mb-0">

                                        {{ $coupons->where('status', 1)->count() }}

                                    </h3>

                                </div>

                                <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center"
                                    style="
                                        width:60px;
                                        height:60px;
                                    ">

                                    <i class="ti ti-circle-check fs-3"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- EXPIRED --}}

                <div class="col-xl-3 col-md-6">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <p class="text-muted mb-1">
                                        Expired
                                    </p>

                                    <h3 class="fw-bold text-danger mb-0">

                                        {{ $coupons->where('valid_until', '<', now())->count() }}

                                    </h3>

                                </div>

                                <div class="bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center"
                                    style="
                                        width:60px;
                                        height:60px;
                                    ">

                                    <i class="ti ti-calendar-off fs-3"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- USED --}}

                <div class="col-xl-3 col-md-6">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <p class="text-muted mb-1">
                                        Total Used
                                    </p>

                                    <h3 class="fw-bold text-warning mb-0">

                                        {{ $coupons->sum('used_count') }}

                                    </h3>

                                </div>

                                <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center"
                                    style="
                                        width:60px;
                                        height:60px;
                                    ">

                                    <i class="ti ti-discount-2 fs-3"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- MAIN TABLE CARD --}}

            <div class="card border-0 shadow-sm">

                {{-- HEADER --}}

                <div class="card-header bg-white border-0">

                    <div class="row align-items-center">

                        {{-- FILTER FORM --}}

                        <form method="GET" action="{{ route('coupon.index') }}">

                            <div class="row align-items-center">

                                {{-- SEARCH --}}

                                <div class="col-lg-4">

                                    <div class="position-relative">

                                        <i class="ti ti-search position-absolute"
                                            style="
                                            left:15px;
                                            top:50%;
                                            transform:translateY(-50%);
                                            color:#9ca3af;
                                            z-index:10;">

                                        </i>

                                        <input type="text" name="search" value="{{ request('search') }}"
                                            class="form-control ps-5" placeholder="Search coupons...">

                                    </div>

                                </div>

                                {{-- FILTERS --}}

                                <div class="col-lg-8">

                                    <div class="
                                        d-flex
                                        justify-content-end
                                        gap-2
                                        mt-3
                                        mt-lg-0">

                                        {{-- STATUS --}}

                                        <select name="status" class="form-select w-auto">

                                            <option value="">
                                                All Status
                                            </option>

                                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>
                                                Active
                                            </option>

                                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>
                                                Inactive
                                            </option>

                                        </select>

                                        {{-- TYPE --}}

                                        <select name="type" class="form-select w-auto">

                                            <option value="">
                                                All Types
                                            </option>

                                            <option value="amount" {{ request('type') == 'amount' ? 'selected' : '' }}>
                                                Amount
                                            </option>

                                            <option value="percentage"
                                                {{ request('type') == 'percentage' ? 'selected' : '' }}>
                                                Percentage
                                            </option>

                                        </select>

                                        {{-- SUBMIT --}}

                                        <button type="submit" class="btn btn-primary">

                                            <i class="ti ti-filter"></i>

                                            Filter

                                        </button>

                                        {{-- RESET --}}

                                        <a href="{{ route('coupon.index') }}" class="btn btn-light">

                                            Reset

                                        </a>

                                    </div>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>

                {{-- TABLE --}}

                <div class="card-body p-0">

                    <x-table.table class="table table-hover align-middle mb-0">

                        {{-- HEADER --}}
                        <x-table.thead>

                            <x-table.th width="70">#</x-table.th>

                            <x-table.th>
                                Coupon
                            </x-table.th>

                            <x-table.th width="130">
                                Type
                            </x-table.th>

                            <x-table.th width="150">
                                Amount
                            </x-table.th>

                            <x-table.th width="160">
                                Usage
                            </x-table.th>

                            <x-table.th width="180">
                                Validity
                            </x-table.th>

                            <x-table.th width="120">
                                Status
                            </x-table.th>

                            <x-table.th width="180" class="text-end">
                                Actions
                            </x-table.th>

                        </x-table.thead>

                        {{-- BODY --}}
                        <x-table.tbody>

                            @forelse ($coupons as $index => $coupon)
                                <x-table.tr>

                                    {{-- INDEX --}}
                                    <x-table.td>

                                        <span class="fw-semibold text-muted">
                                            {{ $coupons->firstItem() + $index }}
                                        </span>

                                    </x-table.td>

                                    {{-- COUPON --}}
                                    <x-table.td>

                                        <div class="d-flex flex-column">

                                            {{-- NAME --}}
                                            <h6 class="fw-semibold mb-2">
                                                {{ $coupon->name }}
                                            </h6>

                                            {{-- CODE --}}
                                            <div class="d-inline-flex align-items-center gap-2">

                                                <span class="badge badge-md bg-outline-danger"
                                                    id="coupon-code-{{ $coupon->id }}">

                                                    {{ $coupon->code }}

                                                </span>

                                                {{-- COPY BUTTON --}}
                                                <button type="button" class="btn btn-icon btn-sm btn-success"
                                                    onclick="copyCouponCode('{{ $coupon->code }}')" title="Copy Coupon">

                                                    <i class="ti ti-copy"></i>

                                                </button>

                                            </div>

                                        </div>

                                    </x-table.td>

                                    {{-- TYPE --}}
                                    <x-table.td>

                                        @if ($coupon->type === 'amount')
                                            <span
                                                class="badge bg-outline-danger">

                                                Amount

                                            </span>
                                        @else
                                            <span
                                                class="badge bg-outline-success">

                                                Percentage

                                            </span>
                                        @endif

                                    </x-table.td>

                                    {{-- AMOUNT --}}
                                    <x-table.td>

                                        <h6 class="fw-bold mb-0">

                                            @if ($coupon->type === 'amount')
                                                {{ currency($coupon->amount) }}
                                            @else
                                               {{ active_currency_symbol() }} {{ number_format($coupon->amount, 0) }}%
                                            @endif

                                        </h6>

                                    </x-table.td>

                                    {{-- USAGE --}}
                                    <x-table.td>

                                        <div class="small">
                                            <div>
                                                Used:
                                                <strong>
                                                     {{ $coupon->usage_limit ?: 'Unlimited' }}
                                                </strong>
                                            </div>
                                        </div>

                                    </x-table.td>

                                    {{-- VALIDITY --}}
                                    <x-table.td>

                                        <div class="small">

                                            <div>

                                                {{ $coupon->valid_from ? \Carbon\Carbon::parse($coupon->valid_from)->format('d M Y') : '-' }}

                                            </div>

                                            <div class="text-muted">

                                                to

                                                {{ $coupon->valid_until ? \Carbon\Carbon::parse($coupon->valid_until)->format('d M Y') : '-' }}

                                            </div>

                                        </div>

                                    </x-table.td>

                                    {{-- STATUS --}}
                                    <x-table.td>

                                        @if ($coupon->status)
                                            <span
                                                class="badge bg-success">

                                                Active

                                            </span>
                                        @else
                                            <span
                                                class="badge bg-danger">

                                                Inactive

                                            </span>
                                        @endif

                                    </x-table.td>

                                    {{-- ACTIONS --}}
                                    <x-table.action-buttons :viewUrl="route('coupon.show', $coupon->id)" viewPermission="coupons.view" :editUrl="route('coupon.edit', $coupon->id)"
                                        editPermission="coupons.edit" :editData="$coupon" :deleteUrl="route('coupon.destroy', $coupon->id)"
                                        deletePermission="coupons.delete" :deleteId="$coupon->id" />

                                </x-table.tr>

                            @empty

                                <x-table.empty colspan="8" title="No Coupons Found"
                                    subtitle="Create your first coupon." />
                            @endforelse

                        </x-table.tbody>

                    </x-table.table>

                </div>

                {{-- FOOTER --}}

                @if ($coupons->hasPages())
                    <div class="card-footer bg-white border-0">

                        {{ $coupons->links('pagination::bootstrap-5') }}

                    </div>
                @endif

            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        /*
                            |--------------------------------------------------------------------------
                            | Copy Coupon Code
                            |--------------------------------------------------------------------------
                            */

        function copyCouponCode(code) {
            navigator.clipboard.writeText(code);

            toastr.success(
                'Coupon code copied!'
            );
        }
    </script>
@endpush
