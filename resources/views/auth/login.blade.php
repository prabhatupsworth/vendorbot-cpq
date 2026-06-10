@extends('layouts.auth')

@section('title', 'Login')
<style>
    .auth-container {
        min-height: 100vh;
    }

    .auth-left {
        width: 45%;
        background: #fff;
        overflow-y: auto;
    }

    .auth-right {
        width: 55%;
        background-image: url('https://genussmarathon.events/wp-content/uploads/Gemini_Generated_Image_342dh0342dh0342d.png');
        background-repeat: no-repeat;
        background-position: center center;
        background-size: cover;
        /* background-size: 100% 100%; */
    }
    @media (max-width: 991px) {
        .auth-left {
            width: 100%;
        }

        .auth-right {
            display: none;
        }
    }
</style>
@section('content')
    <div class="d-flex auth-container">

        <!-- Left Side Login Form -->
        <div class="auth-left d-flex align-items-center justify-content-center p-4">

            <form method="POST" action="{{ route('login.post') }}" class="w-100">
                @csrf

                <div class="mx-auto" style="max-width:450px;">

                    <div class="text-center mb-5">
                        <img src="{{ asset('template/assets/img/vb_logo.png') }}" alt="Vendorbot Logo" width="150"
                            class="img-fluid">
                    </div>

                    <div class="mb-4">
                        <h3 class="fw-bold mb-2">Sign In</h3>
                        {{-- <p class="text-muted mb-0">
                            Access the CRMS panel using your email and passcode.
                        </p> --}}
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>

                        <div class="position-relative">
                            <span class="input-icon-addon">
                                <i class="ti ti-mail"></i>
                            </span>

                            <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                                placeholder="Enter your email">
                        </div>

                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>

                        <div class="pass-group">
                            <input type="password" name="password" class="pass-input form-control"
                                placeholder="Enter your password">

                            <span class="ti toggle-password ti-eye-off"></span>
                        </div>

                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="d-flex  justify-content-end  align-items-center mb-4">

                        {{-- <div class="form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">

                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div> --}}

                        <a href="{{ route('password.request') }}" class="text-primary text-decoration-none">
                            Forgot Password?
                        </a>

                    </div>

                    <div class="mb-4">
                        <button type="submit" class="btn btn-primary w-100">
                            Sign In
                        </button>
                    </div>

                    @include('auth.include.footer')

                </div>

            </form>

        </div>

        <!-- Right Side Image -->
        <div class="auth-right"></div>

    </div>
@endsection
