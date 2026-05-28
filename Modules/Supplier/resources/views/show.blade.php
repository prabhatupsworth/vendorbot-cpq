@extends('layouts.app')

@section('content')
    @php

        $workingHours = json_decode($supplier->daysoff, true);

        $days = [
            'mo' => 'Monday',

            'di' => 'Tuesday',

            'mi' => 'Wednesday',

            'do' => 'Thursday',

            'fr' => 'Friday',

            'sa' => 'Saturday',

            'so' => 'Sunday',
        ];

    @endphp

    <div class="page-wrapper">

        <div class="content">

            <div class="page-header">

                <div class="row align-items-center">

                    <div class="col-md-8">

                        <h3 class="page-title mb-1">
                            {{ $supplier->name }}
                        </h3>

                        <p class="text-muted mb-0">

                            <i class="ti ti-map-pin"></i>

                            {{ $supplier->city }},
                            {{ $supplier->countryData->name ?? '-' }}

                        </p>

                    </div>

                    <div class="col-md-4 text-end">

                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-primary">
                            <i class="ti ti-edit"></i>
                            Edit Supplier
                        </a>

                    </div>

                </div>

            </div>

            <div class="row">

                {{-- LEFT SIDE --}}

                <div class="col-lg-8">

                    {{-- CONTACT INFORMATION --}}

                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white">

                            <h5 class="mb-0">
                                <i class="ti ti-phone me-2"></i>
                                Contact Information
                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-6 mb-4">

                                    <label class="text-muted small">
                                        Email
                                    </label>

                                    <h6 class="mb-0">
                                        {{ $supplier->email ?: '-' }}
                                    </h6>

                                </div>

                                <div class="col-md-6 mb-4">

                                    <label class="text-muted small">
                                        Phone
                                    </label>

                                    <h6 class="mb-0">
                                        {{ $supplier->phone ?: '-' }}
                                    </h6>

                                </div>

                                <div class="col-md-12">

                                    <label class="text-muted small">
                                        Website
                                    </label>

                                    <div>

                                        @if ($supplier->url)
                                            <a href="{{ $supplier->url }}" target="_blank" class="text-primary">
                                                {{ $supplier->url }}
                                            </a>
                                        @else
                                            -
                                        @endif

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- ADDRESS --}}

                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white">

                            <h5 class="mb-0">

                                <i class="ti ti-map-pin me-2"></i>

                                Address

                            </h5>

                        </div>

                        <div class="card-body">

                            <p class="mb-1">
                                {{ $supplier->street }}
                            </p>

                            <p class="mb-1">
                                {{ $supplier->zip }}
                                {{ $supplier->city }}
                            </p>

                            <p class="mb-0">
                                {{ $supplier->countryData->name ?? '-' }}
                            </p>

                        </div>

                    </div>

                    {{-- CATEGORIES --}}

                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white">

                            <h5 class="mb-0">

                                <i class="ti ti-category me-2"></i>

                                Categories

                            </h5>

                        </div>

                        <div class="card-body">

                            @forelse ($supplier->categories
                                            as $category)
                                <span class="badge bg-primary me-2 mb-2 p-2">
                                    {{ $category->name }}
                                </span>

                            @empty

                                <span class="text-muted">
                                    No categories found
                                </span>
                            @endforelse

                        </div>

                    </div>

                    {{-- MAP --}}

                    @if ($supplier->lat && $supplier->lon)
                        <div class="card border-0 shadow-sm">

                            <div class="card-header bg-white">

                                <h5 class="mb-0">

                                    <i class="ti ti-map me-2"></i>

                                    Location Map

                                </h5>

                            </div>

                            <div class="card-body p-0">

                                <iframe width="100%" height="350" frameborder="0" style="border:0"
                                    src="https://maps.google.com/maps?q={{ $supplier->lat }},{{ $supplier->lon }}&z=15&output=embed"
                                    allowfullscreen>
                                </iframe>

                            </div>

                        </div>
                    @endif

                </div>

                {{-- RIGHT SIDE --}}

                <div class="col-lg-4">

                    {{-- BUSINESS INFO --}}

                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white">

                            <h5 class="mb-0">

                                <i class="ti ti-building-store me-2"></i>

                                Supplier Info

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="mb-4">

                                <label class="text-muted small">
                                    Status
                                </label>

                                <div>
                                    <span class="badge bg-{{ $supplier->status?->badge() }}">
                                        {{ $supplier->status?->label() }}
                                    </span>
                                </div>

                            </div>

                            <div class="mb-4">

                                <label class="text-muted small">
                                    Capacity
                                </label>

                                <h5>
                                    {{ $supplier->capacity ?: 0 }}
                                </h5>

                            </div>

                            <div class="mb-4">

                                <label class="text-muted small">
                                    Contact Person
                                </label>

                                <h6>
                                    {{ $supplier->cp_name ?: '-' }}
                                </h6>

                            </div>

                            {{-- DAYS OFF --}}

                            <div>

                                <label class="text-muted small d-block mb-3">
                                    Days Off
                                </label>

                                <div class="d-flex flex-wrap gap-3">

                                    @foreach ($days as $key => $label)
                                        @php

                                            $isOpen = isset($workingHours[$key]) && (int) $workingHours[$key] === 1;
                                        @endphp

                                        <div class="form-check">

                                            <input class="form-check-input" type="checkbox" checked="{{ $isOpen }}"
                                                disabled>

                                            <label class="form-check-label small">
                                                {{ $label }}
                                            </label>

                                        </div>
                                    @endforeach

                                </div>


                            </div>

                        </div>

                    </div>

                    {{-- SOCIAL MEDIA --}}

                    <div class="card border-0 shadow-sm">

                        <div class="card-header bg-white">

                            <h5 class="mb-0">

                                <i class="ti ti-brand-instagram me-2"></i>

                                Social Media

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="d-grid gap-2">

                                @if ($supplier->social_facebook)
                                    <a href="{{ $supplier->social_facebook }}" target="_blank"
                                        class="btn btn-outline-primary">

                                        <i class="ti ti-brand-facebook me-2"></i>

                                        Facebook

                                    </a>
                                @endif

                                @if ($supplier->social_instagram)
                                    <a href="{{ $supplier->social_instagram }}" target="_blank"
                                        class="btn btn-outline-danger">

                                        <i class="ti ti-brand-instagram me-2"></i>

                                        Instagram

                                    </a>
                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection
