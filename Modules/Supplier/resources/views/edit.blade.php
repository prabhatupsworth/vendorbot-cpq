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

            {{-- PAGE HEADER --}}

            <div class="page-header mb-4">

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center flex-wrap">

                            <div>

                                <h2 class="fw-bold mb-1">
                                    {{ $supplier->name }}
                                </h2>

                                <p class="text-muted mb-3">

                                    <i class="ti ti-map-pin me-1"></i>

                                    {{ $supplier->city }},
                                    {{ $supplier->countryData->name ?? '-' }}

                                </p>

                            </div>

                            <div>

                                <span class="badge bg-{{ $supplier->status?->badge() }}">
                                    {{ $supplier->status?->label() }}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="row">

                    {{-- LEFT SIDE --}}

                    <div class="col-lg-8">

                        {{-- BASIC INFORMATION --}}

                        <div class="card border-0 shadow-sm mb-4">

                            <div class="card-header bg-white">

                                <h5 class="mb-0">

                                    <i class="ti ti-user me-2"></i>

                                    Basic Information

                                </h5>

                            </div>

                            <div class="card-body">

                                <div class="row">

                                    {{-- NAME --}}

                                    <div class="col-md-6">

                                        <div class="mb-4">

                                            <label class="form-label">
                                                Supplier Name
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text">

                                                    <i class="ti ti-building-store"></i>

                                                </span>

                                                <input placeholder="Enter supplier name" type="text" name="name"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    value="{{ old('name', $supplier->name) }}">

                                                @error('name')
                                                    <div class="invalid-feedback">

                                                        {{ $message }}

                                                    </div>
                                                @enderror


                                            </div>

                                        </div>

                                    </div>

                                    {{-- CITY --}}

                                    <div class="col-md-6">

                                        <div class="mb-4">

                                            <label class="form-label">
                                                City
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text">

                                                    <i class="ti ti-map-pin"></i>

                                                </span>

                                                <input placeholder="Enter city" type="text" name="city"
                                                    class="form-control form-control @error('city') is-invalid @enderror"
                                                    value="{{ old('city', $supplier->city) }}">

                                                @error('city')
                                                    <div class="invalid-feedback">

                                                        {{ $message }}

                                                    </div>
                                                @enderror

                                            </div>

                                        </div>

                                    </div>

                                    {{-- COUNTRY --}}

                                    <div class="col-md-6">

                                        <div class="mb-4">

                                            <label class="form-label">
                                                Country
                                            </label>

                                            <select name="country"
                                                class="form-select select2 @error('country') is-invalid @enderror">

                                                <option value="">
                                                    Select Country
                                                </option>

                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->code }}"
                                                        {{ old('country', $supplier->country) == $country->code ? 'selected' : '' }}>

                                                        {{ $country->name }}
                                                        ({{ strtoupper($country->code) }})
                                                    </option>
                                                @endforeach
                                                @error('country')
                                                    <div class="invalid-feedback">

                                                        {{ $message }}

                                                    </div>
                                                @enderror

                                            </select>

                                        </div>

                                    </div>


                                    <div class="col-md-6">
                                        <div class="mb-4">

                                            <label class="form-label">
                                                Status
                                            </label>
                                            <select name="status" class="select @error('status') is-invalid @enderror">

                                                @foreach (\Modules\Supplier\Enums\SupplierStatusEnum::cases() as $status)
                                                    <option value="{{ $status->value }}"
                                                        {{ old('status', $supplier->status?->value) == $status->value ? 'selected' : '' }}>

                                                        {{ $status->label() }}

                                                    </option>
                                                @endforeach

                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">

                                                    {{ $message }}

                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">

                                        <div class="mb-4">

                                            <label class="form-label">
                                                Salutation to the contact person
                                            </label>

                                            <select name="cp_title"
                                                class="form-select select2 @error('status') is-invalid @enderror"
                                                aria-label="Title Contact person">

                                                <option value="">
                                                    --- no selection ---
                                                </option>

                                                <option value="1"
                                                    {{ old('cp_title', $supplier->cp_title) == '1' ? 'selected' : '' }}>
                                                    Woman
                                                </option>

                                                <option value="2"
                                                    {{ old('cp_title', $supplier->cp_title) == '2' ? 'selected' : '' }}>
                                                    Mister
                                                </option>

                                                <option value="3"
                                                    {{ old('cp_title', $supplier->cp_title) == '3' ? 'selected' : '' }}>
                                                    Diverse
                                                </option>

                                            </select>
                                            @error('cp_title')
                                                <div class="invalid-feedback">

                                                    {{ $message }}

                                                </div>
                                            @enderror
                                        </div>

                                    </div>

                                    {{-- CONTACT PERSON --}}

                                    <div class="col-md-6">

                                        <div class="mb-4">

                                            <label class="form-label">
                                                Contact Person
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text">

                                                    <i class="ti ti-user-circle"></i>

                                                </span>

                                                <input placeholder="Enter contact person name" type="text" name="cp_name"
                                                    class="form-control @error('cp_name') is-invalid @enderror"
                                                    value="{{ old('cp_name', $supplier->cp_name) }}">
                                                @error('cp_name')
                                                    <div class="invalid-feedback">

                                                        {{ $message }}

                                                    </div>
                                                @enderror
                                            </div>

                                        </div>

                                    </div>

                                    {{-- CAPACITY --}}

                                    <div class="col-md-6">

                                        <div class="mb-4">

                                            <label class="form-label">
                                                Capacity
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text">

                                                    <i class="ti ti-users"></i>

                                                </span>

                                                <input placeholder="Enter capacity" type="number" name="capacity"
                                                    class="form-control  @error('capacity') is-invalid @enderror"
                                                    value="{{ old('capacity', $supplier->capacity) }}">
                                                @error('capacity')
                                                    <div class="invalid-feedback">

                                                        {{ $message }}

                                                    </div>
                                                @enderror
                                            </div>

                                        </div>

                                    </div>


                                </div>

                            </div>

                        </div>

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

                                    {{-- EMAIL --}}

                                    <div class="col-md-6">

                                        <div class="mb-4">

                                            <label class="form-label">
                                                Email
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text">

                                                    <i class="ti ti-mail"></i>

                                                </span>

                                                <input placeholder="Enter email" type="email" name="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    value="{{ old('email', $supplier->email) }}">
                                                @error('email')
                                                    <div class="invalid-feedback">

                                                        {{ $message }}

                                                    </div>
                                                @enderror

                                            </div>

                                        </div>

                                    </div>

                                    {{-- PHONE --}}

                                    <div class="col-md-6">

                                        <div class="mb-4">

                                            <label class="form-label">
                                                Phone
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text">

                                                    <i class="ti ti-phone"></i>

                                                </span>

                                                <input placeholder="Enter phone" type="text" name="phone"
                                                    class="form-control @error('phone') is-invalid @enderror"
                                                    value="{{ old('phone', $supplier->phone) }}">
                                                @error('phone')
                                                    <div class="invalid-feedback">

                                                        {{ $message }}

                                                    </div>
                                                @enderror

                                            </div>

                                        </div>

                                    </div>

                                    {{-- WEBSITE --}}

                                    <div class="col-md-6">

                                        <div class="mb-4">

                                            <label class="form-label">
                                                Website
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text">

                                                    <i class="ti ti-world-www"></i>

                                                </span>

                                                <input placeholder="Enter website url" type="text" name="url"
                                                    class="form-control  @error('url') is-invalid @enderror"
                                                    value="{{ old('url', $supplier->url) }}">
                                                @error('url')
                                                    <div class="invalid-feedback">

                                                        {{ $message }}

                                                    </div>
                                                @enderror
                                            </div>

                                        </div>

                                    </div>

                                    {{-- FACEBOOK --}}

                                    <div class="col-md-6">

                                        <div class="mb-4">

                                            <label class="form-label">
                                                Facebook
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text">

                                                    <i class="ti ti-brand-facebook"></i>

                                                </span>

                                                <input placeholder="Enter facebook url" type="text"
                                                    name="social_facebook"
                                                    class="form-control  @error('social_facebook') is-invalid @enderror"
                                                    value="{{ old('social_facebook', $supplier->social_facebook) }}">
                                                @error('social_facebook')
                                                    <div class="invalid-feedback">

                                                        {{ $message }}

                                                    </div>
                                                @enderror

                                            </div>

                                        </div>

                                    </div>

                                    {{-- INSTAGRAM --}}

                                    <div class="col-md-6">

                                        <div class="mb-4">

                                            <label class="form-label">
                                                Instagram
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text">

                                                    <i class="ti ti-brand-instagram"></i>

                                                </span>

                                                <input placeholder="Enter Instagram url" type="text"
                                                    name="social_instagram"
                                                    class="form-control  @error('social_instagram') is-invalid @enderror"
                                                    value="{{ old('social_instagram', $supplier->social_instagram) }}">
                                                @error('social_instagram')
                                                    <div class="invalid-feedback">

                                                        {{ $message }}

                                                    </div>
                                                @enderror

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- ADDRESS --}}

                        <div class="card border-0 shadow-sm mb-4">

                            <div class="card-header bg-white">

                                <h5 class="mb-0">

                                    <i class="ti ti-map-2 me-2"></i>

                                    Address & Location

                                </h5>

                            </div>

                            <div class="card-body">

                                <div class="row">

                                    {{-- STREET --}}

                                    <div class="col-md-6">

                                        <div class="mb-4">

                                            <label class="form-label">
                                                Street
                                            </label>

                                            <input type="text" name="street"
                                                class="form-control @error('street') is-invalid @enderror"
                                                value="{{ old('street', $supplier->street) }}">

                                            @error('street')
                                                <div class="invalid-feedback">

                                                    {{ $message }}

                                                </div>
                                            @enderror

                                        </div>

                                    </div>

                                    {{-- POSTCODE --}}

                                    <div class="col-md-6">

                                        <div class="mb-4">

                                            <label class="form-label">
                                                Postcode
                                            </label>

                                            <input placeholder="Enter postal code" type="text" name="zip"
                                                class="form-control @error('zip') is-invalid @enderror"
                                                value="{{ old('zip', $supplier->zip) }}">

                                            @error('zip')
                                                <div class="invalid-feedback">

                                                    {{ $message }}

                                                </div>
                                            @enderror

                                        </div>

                                    </div>

                                    {{-- LATITUDE --}}

                                    <div class="col-md-6">

                                        <div class="mb-4">

                                            <label class="form-label">
                                                Latitude
                                            </label>

                                            <input placeholder="Enter lat" type="text" name="lat"
                                                class="form-control @error('lat') is-invalid @enderror"
                                                value="{{ old('lat', $supplier->lat) }}">
                                            @error('lat')
                                                <div class="invalid-feedback">

                                                    {{ $message }}

                                                </div>
                                            @enderror

                                        </div>

                                    </div>

                                    {{-- LONGITUDE --}}

                                    <div class="col-md-6">

                                        <div class="mb-4">

                                            <label class="form-label">
                                                Longitude
                                            </label>

                                            <input type="text" name="lon"
                                                class="form-control @error('lon') is-invalid @enderror"
                                                value="{{ old('lon', $supplier->lon) }}" placeholder="Enter Lon">

                                            @error('lon')
                                                <div class="invalid-feedback">

                                                    {{ $message }}

                                                </div>
                                            @enderror

                                        </div>

                                    </div>

                                </div>

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

                                <select name="categories[]" class="form-select select2 @error('categories') is-invalid @enderror" multiple>

                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ in_array($category->id, $supplier->categories->pluck('id')->toArray()) ? 'selected' : '' }}>

                                            {{ $category->name }}

                                        </option>
                                    @endforeach

                                </select>
                                @error('categories')
                                    <div class="invalid-feedback">

                                        {{ $message }}

                                    </div>
                                @enderror

                            </div>

                        </div>


                        <div class="card border-0 shadow-sm mb-4">

                            <div class="card-header bg-white">

                                <h5 class="mb-0">

                                    <i class="ti ti-category me-2"></i>

                                    Supplier Comments (visible and editable by restaurants):

                                </h5>

                            </div>

                            <div class="card-body">

                                <textarea placeholder="Enter notice" name="notice" class="form-control @error('notice') is-invalid @enderror" rows="4">{{ old('notice', $supplier->notice) }}</textarea>
                                @error('notice')
                                    <div class="invalid-feedback">

                                        {{ $message }}

                                    </div>
                                @enderror

                            </div>

                        </div>



                        <div class="card border-0 shadow-sm mb-4">

                            <div class="card-header bg-white">

                                <h5 class="mb-0">

                                    <i class="ti ti-category me-2"></i>

                                    Internal Note (visible and editable only by admins):

                                </h5>

                            </div>

                            <div class="card-body">

                                <textarea placeholder="Enter notice intern" name="notice_intern" class="form-control @error('notice_intern') is-invalid @enderror" rows="4">{{ old('notice_intern', $supplier->notice_intern) }}</textarea>
                                @error('notice_intern')
                                    <div class="invalid-feedback">

                                        {{ $message }}

                                    </div>
                                @enderror

                            </div>



                        </div>


                    </div>

                    {{-- RIGHT SIDE --}}

                    <div class="col-lg-4">

                        {{-- DAYS OFF --}}

                        <div class="card border-0 shadow-sm mb-4">

                            <div class="card-header bg-white">

                                <h5 class="mb-0">

                                    <i class="ti ti-calendar-off me-2"></i>

                                    Days Off

                                </h5>

                            </div>

                            <div class="card-body">

                                <div class="d-flex flex-wrap gap-3">

                                    @foreach ($days as $key => $label)
                                        @php

                                            $isOpen = isset($workingHours[$key]) && $workingHours[$key] == 1;
                                        @endphp

                                        <div class="form-check">

                                            <input class="form-check-input" type="checkbox" name="days_off[]"
                                                value="{{ $key }}" id="{{ $key }}"
                                                {{ $isOpen ? 'checked' : '' }}>

                                            <label class="form-check-label" for="{{ $key }}">
                                                {{ $label }}
                                            </label>

                                        </div>
                                    @endforeach

                                </div>

                            </div>

                        </div>

                        {{-- MAP PREVIEW --}}

                        @if ($supplier->lat && $supplier->lon)
                            <div class="card border-0 shadow-sm mb-4">

                                <div class="card-header bg-white">

                                    <h5 class="mb-0">

                                        <i class="ti ti-map me-2"></i>

                                        Map Preview

                                    </h5>

                                </div>

                                <div class="card-body p-0">

                                    <iframe width="100%" height="300" frameborder="0" style="border:0"
                                        src="https://maps.google.com/maps?q={{ $supplier->lat }},{{ $supplier->lon }}&z=15&output=embed"
                                        allowfullscreen>
                                    </iframe>

                                </div>

                            </div>
                        @endif

                    </div>

                </div>

                {{-- SAVE BAR --}}

                <div class="position-sticky bottom-0 bg-white p-3 shadow-lg rounded mt-4" style="z-index:1000">

                    <div class="d-flex justify-content-end gap-2">

                        <a href="{{ route('suppliers.index') }}" class="btn btn-light">
                            Cancel
                        </a>

                        <button type="submit" class="btn btn-primary">

                            <i class="ti ti-device-floppy me-1"></i>

                            Save Changes

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>
@endsection
