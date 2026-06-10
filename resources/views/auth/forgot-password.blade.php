@extends('layouts.auth')
@section('title', 'Forgot Password')
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
            <form method="POST" action="{{route('password.email')}}" class="flex-fill">
                @csrf
                <div class="mx-auto mw-450">
                    <div class="text-center mb-4">
                        <img src="{{ asset('template/assets/img/vb_logo.png')}}" width="150" class="img-fluid" alt="Logo">
                    </div>
                    <div class="mb-4">
                        <h4 class="mb-2 fs-20">Forgot Password?</h4>
                        <p>If you forgot your password, well, then we’ll email you instructions to reset your
                            password.</p>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Email Address</label>
                        <div class="position-relative">
                            <span class="input-icon-addon">
                                <i class="ti ti-mail"></i>
                            </span>
                            <input name="email" type="text" value="{{old('email')}}" class="form-control">
                        </div>
                        @error('email')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                    </div>
                    <div class="mb-3 text-center">
                        <h6>Return to <a href="{{route('login')}}" class="text-purple link-hover"> Login</a></h6>
                    </div>

                    @include('auth.include.footer')
                </div>
            </form>
        </div>

         <!-- Right Side Image -->
        <div class="auth-right"></div>
    </div>
@endsection
