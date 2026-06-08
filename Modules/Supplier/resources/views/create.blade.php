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

                                                <input placeholder="Enter supplier name" required type="text"
                                                    name="name" class="form-control @error('name') is-invalid @enderror"
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
                                                <span class="text-danger">*</span>
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text">

                                                    <i class="ti ti-map-pin"></i>

                                                </span>

                                                <input required placeholder="Enter city" type="text" name="city"
                                                    class="form-control @error('city') is-invalid @enderror"
                                                    value="{{ old('city') }}">

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
                                                class="form-select select2 @error('city') is-invalid @enderror">

                                                <option value="">
                                                    Select Country
                                                </option>

                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->code }}" @selected(old('country') == $country->code)>

                                                        {{ $country->name }}

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

                                    {{-- STATUS --}}
                                    <div class="col-md-6">

                                        <div class="mb-4">

                                            <label class="form-label">
                                                Status
                                            </label>

                                            <select name="status" class="select @error('status') is-invalid @enderror">

                                                @foreach (\Modules\Supplier\Enums\SupplierStatusEnum::cases() as $status)
                                                    <option value="{{ $status->value }}">

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
                                                class="form-select select2 @error('cp_title') is-invalid @enderror"
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

                                                <input
                                                    placeholder="Enter contact person @error('cp_name') is-invalid @enderror"
                                                    type="text" name="cp_name" class="form-control"
                                                    value="{{ old('cp_name') }}">
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

                                                <input placeholder="Enter Capacity" type="number" name="capacity"
                                                    class="form-control @error('capacity') is-invalid @enderror"
                                                    value="{{ old('capacity') }}">
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
                                                <span class="text-danger">*</span>
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text">

                                                    <i class="ti ti-mail"></i>

                                                </span>

                                                <input placeholder="Enter email" required type="email" name="email"
                                                    class="form-control @error('email') is-invalid @enderror"
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

                                                <input type="text" name="phone"
                                                    class="form-control @error('phone') is-invalid @enderror"
                                                    value="{{ old('phone') }}" placeholder="Enter Phone">
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

                                                <input type="text" name="url"
                                                    class="form-control @error('url') is-invalid @enderror"
                                                    value="{{ old('url') }}" placeholder="Enter website">
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

                                                <input type="text" name="social_facebook"
                                                    class="form-control @error('social_facebook') is-invalid @enderror"
                                                    value="{{ old('social_facebook') }}" placeholder="Enter facebook url">
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

                                                <input type="text" name="social_instagram"
                                                    class="form-control @error('social_instagram') is-invalid @enderror"
                                                    value="{{ old('social_instagram') }}" placeholder="Enter instagram placeholder">
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
                                                value="{{ old('street') }}" placeholder="Enter Street">
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

                                            <input type="text" name="zip"
                                                class="form-control @error('zip') is-invalid @enderror"
                                                value="{{ old('zip') }}" placeholder="Enter postal code">
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

                                            <input type="text" name="lat"
                                                class="form-control @error('lat') is-invalid @enderror"
                                                value="{{ old('lat') }}" placeholder="0.03">

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
                                                value="{{ old('lon') }}" placeholder="0.05">

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
                                    <span class="text-danger">*</span>
                                </h5>

                            </div>

                            <div class="card-body">

                                <select required name="categories[]"
                                    class="form-select select2 @error('categories') is-invalid @enderror" multiple>

                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">

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

                                <textarea placeholder="Enter notice" name="notice" class="form-control @error('notice') is-invalid @enderror" rows="4">{{ old('notice') }}</textarea>
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

                                <textarea placeholder="Enter notice intern" name="notice_intern" class="form-control @error('notice_intern') is-invalid @enderror" rows="4">{{ old('notice_intern') }}</textarea>
                                @error('notice_intern')
                                    <div class="invalid-feedback">

                                        {{ $message }}

                                    </div>
                                @enderror
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

                                            <input class="form-check-input  @error('days_off') is-invalid @enderror"
                                                type="checkbox" name="days_off[]" value="{{ $key }}"
                                                id="{{ $key }}">

                                            <label class="form-check-label" for="{{ $key }}">

                                                {{ $label }}

                                            </label>
                                            @error('days_off')
                                                <div class="invalid-feedback">

                                                    {{ $message }}

                                                </div>
                                            @enderror

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
