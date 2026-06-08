@extends('layouts.app')

@section('content')
    <div class="page-wrapper">

        <div class="content">

            {{-- PAGE HEADER --}}
            <div class="page-header mb-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h3 class="fw-bold">
                            Create Supplier
                        </h3>

                        <p class="text-muted mb-0">
                            Add a new supplier to the system
                        </p>

                    </div>

                </div>

            </div>

            {{-- FORM --}}
            <form action="{{ route('suppliers.store') }}" method="POST">

                @csrf

                <div class="row">

                    {{-- LEFT --}}
                    <div class="col-lg-8">

                        {{-- BASIC --}}
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
                                                 <span class="text-danger">*</span>
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text">

                                                    <i class="ti ti-building-store"></i>

                                                </span>

                                                <input placeholder="Enter supplier name" required type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                                    value="{{ old('name') }}">
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

                                                <input placeholder="Enter city" type="text" name="city" class="form-control"
                                                    value="{{ old('city') }}">

                                            </div>

                                        </div>

                                    </div>


                                    {{-- COUNTRY --}}
                                    <div class="col-md-6">

                                        <div class="mb-4">

                                            <label class="form-label">
                                                Country
                                            </label>

                                            <select name="country" class="form-select select2">

                                                <option value="">
                                                    Select Country
                                                </option>

                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->code }}" @selected(old('country') == $country->code)>

                                                        {{ $country->name }}

                                                    </option>
                                                @endforeach

                                            </select>

                                        </div>

                                    </div>

                                    {{-- STATUS --}}
                                    <div class="col-md-6">

                                        <div class="mb-4">

                                            <label class="form-label">
                                                Status
                                            </label>

                                            <select name="status" class="select">

                                                @foreach (\Modules\Supplier\Enums\SupplierStatusEnum::cases() as $status)
                                                    <option value="{{ $status->value }}">

                                                        {{ $status->label() }}

                                                    </option>
                                                @endforeach

                                            </select>

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="mb-4">

                                            <label class="form-label">
                                                Salutation to the contact person
                                            </label>

                                            <select name="cp_title" class="form-select select2"
                                                aria-label="Title Contact person">

                                                <option value="">
                                                    --- no selection ---
                                                </option>

                                                <option value="1">
                                                    Woman
                                                </option>

                                                <option value="2">
                                                    Mister
                                                </option>

                                                <option value="3">
                                                    Diverse
                                                </option>

                                            </select>

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

                                                <input placeholder="Enter contact person" type="text" name="cp_name" class="form-control"
                                                    value="{{ old('cp_name') }}">

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

                                                <input placeholder="Enter Capacity" type="number" name="capacity" class="form-control"
                                                    value="{{ old('capacity') }}">

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
                                                 <span class="text-danger">*</span>
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text">

                                                    <i class="ti ti-mail"></i>

                                                </span>

                                                <input placeholder="Enter email" required type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                                    value="{{ old('email') }}">
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

                                                <input type="text" name="phone" class="form-control"
                                                    value="{{ old('phone') }}">

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

                                                <input type="text" name="url" class="form-control"
                                                    value="{{ old('url') }}">

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

                                                <input type="text" name="social_facebook" class="form-control"
                                                    value="{{ old('social_facebook') }}">

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

                                                <input type="text" name="social_instagram" class="form-control"
                                                    value="{{ old('social_instagram') }}">

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

                                            <input type="text" name="street" class="form-control"
                                                value="{{ old('street') }}">

                                        </div>

                                    </div>

                                    {{-- POSTCODE --}}

                                    <div class="col-md-6">

                                        <div class="mb-4">

                                            <label class="form-label">
                                                Postcode
                                            </label>

                                            <input type="text" name="zip" class="form-control"
                                                value="{{ old('zip') }}">

                                        </div>

                                    </div>

                                    {{-- LATITUDE --}}

                                    <div class="col-md-6">

                                        <div class="mb-4">

                                            <label class="form-label">
                                                Latitude
                                            </label>

                                            <input type="text" name="lat" class="form-control"
                                                value="{{ old('lat') }}">

                                        </div>

                                    </div>

                                    {{-- LONGITUDE --}}

                                    <div class="col-md-6">

                                        <div class="mb-4">

                                            <label class="form-label">
                                                Longitude
                                            </label>

                                            <input type="text" name="lon" class="form-control"
                                                value="{{ old('lon') }}">

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

                                <select name="categories[]" class="form-select select2" multiple>

                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">

                                            {{ $category->name }}

                                        </option>
                                    @endforeach

                                </select>

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

                                <textarea name="notice" class="form-control" rows="4">{{ old('notice') }}</textarea>

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

                                <textarea name="notice_intern" class="form-control" rows="4">{{ old('notice_intern') }}</textarea>

                            </div>



                        </div>

                    </div>

                    {{-- RIGHT --}}
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
                                        <div class="form-check">

                                            <input class="form-check-input" type="checkbox" name="days_off[]"
                                                value="{{ $key }}" id="{{ $key }}">

                                            <label class="form-check-label" for="{{ $key }}">

                                                {{ $label }}

                                            </label>

                                        </div>
                                    @endforeach

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- SAVE BAR --}}
                <div class="position-sticky
                       bottom-0
                       bg-white
                       p-3
                       shadow-lg
                       rounded
                       mt-4"
                    style="z-index:1000">

                    <div class="d-flex justify-content-end gap-2">

                        <a href="{{ route('suppliers.index') }}" class="btn btn-light">

                            Cancel

                        </a>

                        <button type="submit" class="btn btn-primary">

                            <i class="ti ti-device-floppy me-1"></i>

                            Create Supplier

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>
@endsection
