@extends('layouts.auth')
@section('title', 'Login')
@section('content')
    <div class="d-flex flex-wrap w-100 vh-100 overflow-hidden account-bg-01">
        <div class="d-flex align-items-center justify-content-center flex-wrap vh-100 overflow-auto p-4 w-50 bg-backdrop">
            <form method="POST" action="{{ route('login.post') }}" class="flex-fill">
                @csrf
                <div class="mx-auto mw-450">
                    <div class="text-center mb-4">
                        <img src="{{ asset('template/assets/img/vb_logo.png') }}" width="150" class="img-fluid"
                            alt="Logo">
                    </div>
                    <div class="mb-4">
                        <h4 class="mb-2 fs-20">Sign In</h4>
                        <p>Access the CRMS panel using your email and passcode.</p>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Email Address</label>
                        <div class="position-relative">
                            <span class="input-icon-addon">
                                <i class="ti ti-mail"></i>
                            </span>
                            <input type="text" name="email" value="{{ old('email') }}" class="form-control">
                        </div>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Password</label>
                        <div class="pass-group">
                            <input name="password" value="{{ old('password') }}" type="password"
                                class="pass-input form-control">
                            <span class="ti toggle-password ti-eye-off"></span>
                        </div>
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="form-check form-check-md d-flex align-items-center">
                            <input name="remember" class="form-check-input" type="checkbox" value="" id="checkebox-md"
                                checked="">
                            <label class="form-check-label" for="checkebox-md">
                                Remember Me
                            </label>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('password.request') }}" class="text-primary fw-medium link-hover">Forgot
                                Password?</a>
                        </div>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary w-100">Sign In</button>
                    </div>
                    @include('auth.include.footer')
                </div>
            </form>
        </div>
    </div>
@endsection
