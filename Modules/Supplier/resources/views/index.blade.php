@extends('layouts.app')
<style>
    .select2-container .select2-search--inline .select2-search__field{
        height: 25px !important;
    }
    .google-strike:before {
    content: " ";
    display: block;
    position: absolute;
    top: 50%;
    left: 0px;
    width: 30px;
    height: 2px;
    background-color: #fff;
    transform: rotate(45deg);
}
</style>
@section('content')

    <div class="page-wrapper">

        <div class="content">

            {{-- PAGE HEADER --}}

            <div class="page-header mb-4">

                <div class="row align-items-center">

                    <div class="col-lg-6">

                        <h3 class="page-title fw-bold">

                            Suppliers

                        </h3>

                        <p class="text-muted mb-0">

                            Manage all suppliers and categories

                        </p>

                    </div>

                    <div class="col-lg-6 text-end">

                        <div class="d-flex justify-content-end gap-2">
                            @if(userCan('suppliers.create'))
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importSupplierModal">
                                    Import Supplier
                                </button>

                                <a href="{{ route('suppliers.create') }}" class="btn btn-primary">

                                    <i class="ti ti-square-rounded-plus me-2"> </i>

                                    Add Supplier

                                </a>
                            @endif
                        </div>

                    </div>

                </div>

            </div>

            {{-- STATS CARDS --}}

            <div class="row">

                <div class="col-xl-3 col-md-6">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <p class="text-muted mb-1">
                                        Total Suppliers
                                    </p>

                                    <h3 class="fw-bold mb-0">

                                        {{ $suppliers->total() }}

                                    </h3>

                                </div>

                                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center"
                                    style="
                            width:60px;
                            height:60px;
                        ">

                                    <i class="ti ti-building-factory fs-3"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                @foreach (\Modules\Supplier\Enums\SupplierStatusEnum::cases() as $status)
                    @continue($status === \Modules\Supplier\Enums\SupplierStatusEnum::NO_STATUS)

                    <div class="col-xl-3 col-md-6">

                        <div class="card border-0 shadow-sm">

                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>

                                        <p class="text-muted mb-1">

                                            {{ $status->label() }}

                                        </p>

                                        <h3 class="fw-bold text-{{ $status->badge() }} mb-0">

                                            {{ $suppliers->where('status', $status)->count() }}

                                        </h3>

                                    </div>

                                    <div class="bg-{{ $status->badge() }}-subtle
                                        text-{{ $status->badge() }}
                                        rounded-circle
                                        d-flex
                                        align-items-center
                                        justify-content-center"
                                        style="width:60px;height:60px;">

                                        <i class="ti ti-chart-bar fs-3"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>

            {{-- MAIN CARD --}}

            <div class="card border-0 shadow-sm">

                {{-- CARD HEADER --}}

                <div class="card-header bg-white border-0">

                    <form method="GET">

                        <div class="row g-3 align-items-end">

                            {{-- SEARCH --}}
                            <div class="col-12 col-lg-4">

                                <label class="form-label fw-semibold">
                                    Search Supplier
                                </label>

                                <div class="position-relative">

                                    <i class="ti ti-search position-absolute"
                                        style="
                                        left:15px;
                                        top:50%;
                                        transform:translateY(-50%);
                                        color:#9ca3af;
                                        z-index:10;">
                                    </i>

                                    <input type="text" name="search" class="form-control ps-5"
                                        placeholder="Search by name, email, phone..." value="{{ request('search') }}">

                                </div>

                            </div>

                            {{-- STATUS --}}
                            <div class="col-12 col-md-6 col-lg-3">

                                <label class="form-label fw-semibold">
                                    Supplier Status
                                </label>

                                <select name="status" class="select">

                                    <option value="">
                                        Select Status
                                    </option>

                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->value }}" @selected(request('status') == $status->value)>

                                            {{ $status->label() }}

                                        </option>
                                    @endforeach

                                </select>

                            </div>

                            {{-- CATEGORY --}}
                            <div class="col-12 col-md-6 col-lg-3">

                                <label class="form-label fw-semibold">
                                    Supplier Categories
                                </label>

                                <select name="category[]" class="select2" multiple="multiple"
                                    data-placeholder="Select Categories">

                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected(in_array($category->id, request('category', [])))>

                                            {{ $category->name }}

                                        </option>
                                    @endforeach

                                </select>

                            </div>

                            {{-- BUTTONS --}}
                            <div class="col-12 col-lg-2 d-flex align-items-end justify-content-end gap-2">

                                <label class="form-label d-none d-lg-block">
                                    &nbsp;
                                </label>

                                <div class="d-flex gap-2">

                                    {{-- FILTER --}}
                                    <button type="submit" class="btn btn-primary flex-fill">

                                        <i class="ti ti-filter"></i>

                                        Filter

                                    </button>

                                    {{-- RESET --}}
                                    <a href="{{ route('suppliers.index') }}" class="btn btn-light flex-fill">

                                        Reset

                                    </a>

                                </div>

                            </div>

                        </div>

                    </form>

                </div>

                {{-- TABLE --}}

                <div class="card-body p-0">

                    <x-table.table>

                        <x-table.thead>
                            <x-table.th width="70">#</x-table.th>
                            <x-table.th>Supplier</x-table.th>
                            <x-table.th width="140">Status</x-table.th>
                            <x-table.th width="220">Location</x-table.th>
                            <x-table.th width="280">Categories</x-table.th>
                            <x-table.th width="180" class="text-end">Actions</x-table.th>
                        </x-table.thead>

                        <x-table.tbody>

                            @forelse ($suppliers as $index => $supplier)
                                <x-table.tr>

                                    {{-- INDEX --}}
                                    <x-table.td>
                                        <span class="fw-semibold text-muted">
                                            {{ $suppliers->firstItem() + $index }}
                                        </span>
                                    </x-table.td>

                                    {{-- SUPPLIER --}}
                                    <x-table.td>

                                        <div class="d-flex align-items-center">

                                            {{-- AVATAR --}}
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                                                style="
                                                width:45px;
                                                height:45px;
                                                font-weight:700;
                                                font-size:18px;">

                                                {{ strtoupper(substr($supplier->name, 0, 1)) }}

                                            </div>

                                            {{-- DETAILS --}}
                                            <div>

                                                <h6 class="mb-1 fw-semibold">

                                                    {{ $supplier->name }}

                                                    @if (blank($supplier->google_id))
                                                        <span
                                                            class="badge rounded-pill bg-danger position-relative google-strike"
                                                            title="keine Google ID">

                                                            <i class="fab fa-google"></i>

                                                        </span>
                                                    @endif

                                                </h6>

                                                <div class="small text-muted mb-1">
                                                    <i class="ti ti-mail me-1"></i>
                                                    {{ $supplier->email ?: 'No Email' }}
                                                </div>

                                                <div class="small text-muted">
                                                    <i class="ti ti-phone me-1"></i>
                                                    {{ $supplier->phone ?: 'No Phone' }}
                                                </div>

                                            </div>

                                        </div>

                                    </x-table.td>

                                    {{-- STATUS --}}
                                    <x-table.td>

                                        <span class="badge bg-{{ $supplier->status?->badge() }}">
                                            {{ $supplier->status?->label() }}
                                        </span>

                                    </x-table.td>

                                    {{-- LOCATION --}}
                                    <x-table.td>

                                        <div class="small">

                                            <div class="fw-medium">
                                                {{ $supplier->city }}
                                            </div>

                                            <div class="text-muted">
                                                {{ $supplier->street }}
                                            </div>

                                            <div class="text-muted">
                                                {{ $supplier->countryData->name ?? '-' }}
                                            </div>

                                        </div>

                                    </x-table.td>

                                    {{-- CATEGORIES --}}
                                    <x-table.td>

                                        <div class="d-flex flex-wrap gap-1">

                                            @foreach ($supplier->categories->take(5) as $category)
                                                <span class="badge bg-dark text-white">
                                                    {{ $category->name }}
                                                </span>
                                            @endforeach

                                            @if ($supplier->categories->count() > 5)
                                                <span class="badge bg-dark text-white">
                                                    +{{ $supplier->categories->count() - 5 }}
                                                </span>
                                            @endif

                                        </div>

                                    </x-table.td>

                                    {{-- ACTIONS --}}
                                    <x-table.action-buttons :viewUrl="route('suppliers.show', $supplier->id)" viewPermission="suppliers.view"
                                        :editUrl="route('suppliers.edit', $supplier->id)" editPermission="suppliers.edit" :editData="$supplier"
                                        :deleteUrl="route('suppliers.destroy', $supplier->id)" deletePermission="suppliers.delete" :deleteId="$supplier->id" />

                                </x-table.tr>

                            @empty
                                <x-table.empty colspan="6" title="No Suppliers Found"
                                    subtitle="Try adjusting your search." />
                            @endforelse

                        </x-table.tbody>

                    </x-table.table>

                </div>

                {{-- PAGINATION --}}

                <div class="card-footer bg-white border-0">

                    {{ $suppliers->links('pagination::bootstrap-5') }}

                </div>

            </div>

            @include('supplier::partials.supplier-import-modal')

        </div>

    </div>

@endsection
