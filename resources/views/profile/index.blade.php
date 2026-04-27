@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="row">
                <div class="col-md-12">

                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col-sm-4">
                                <h4 class="page-title">Settings</h4>
                            </div>
                            <div class="col-sm-8 text-sm-end">
                                <div class="head-icons">
                                    <a href="profile.html" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-original-title="Refresh"><i class="ti ti-refresh-dot"></i></a>
                                    <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-original-title="Collapse" id="collapse-header"><i
                                            class="ti ti-chevrons-up"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->

                    <!-- Settings Menu -->
                    @include('profile.setting-menu')
                    <!-- /Settings Menu -->

                    <div class="row">
                        @include('profile.sidebar')

                        <div class="col-xl-9 col-lg-12">

                            <!-- Settings Info -->
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="fw-semibold mb-3">Profile Settings</h4>
                                    <form action="{{ route('profile.update') }}" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <div class="border-bottom mb-3 pb-3">
                                            <h5 class="fw-semibold mb-1">Employee Information</h5>
                                            <p>Provide the information below</p>
                                        </div>
                                        <div class="mb-3">
                                            <x-image-upload name="profile_image" :value="asset('storage/' . $user->profile_image)" />

                                        </div>
                                        <div class="border-bottom mb-3">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            Name <span class="text-danger">*</span>
                                                        </label>
                                                        <input name="name" type="text" class="form-control"
                                                            value="{{ old('name', $user->name) }}">
                                                        @error('name')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            Email <span class="text-danger">*</span>
                                                        </label>
                                                        <input name="email" type="email" class="form-control"
                                                            value="{{ old('email', $user->email) }}" readonly>
                                                        @error('email')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <a href="#" class="btn btn-light me-2">Cancel</a>
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /Settings Info -->

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection()
