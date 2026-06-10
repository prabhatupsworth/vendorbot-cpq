   @extends('layouts.auth')
   @section('title', 'Reset Password')
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


               <form method="POST" action="{{ route('password.update') }}" class="flex-fill">
                   <input type="hidden" name="token" value="{{ $token }}">
                   <input type="hidden" name="email" value="{{ $email }}">

                   @csrf
                   <div class="mx-auto mw-450">
                       <div class="text-center mb-4">
                           <img src="{{ asset('template/assets/img/vb_logo.png') }}" width="150" class="img-fluid"
                               alt="Logo">
                       </div>
                       <div class="mb-4">
                           <h4 class="mb-2 fs-20">Reset Password?</h4>
                           <p>Enter New Password & Confirm Password to get inside</p>
                       </div>
                       <div class="mb-3">
                           <label class="col-form-label">Password</label>
                           <div class="pass-group">
                               <input name="password" type="password" class="pass-input form-control">
                               <span class="ti toggle-password ti-eye-off"></span>
                           </div>
                           @error('password')
                               <div class="text-danger">{{ $message }}</div>
                           @enderror
                       </div>
                       <div class="mb-3">
                           <label class="col-form-label">Confirm Password</label>
                           <div class="pass-group">
                               <input name="password_confirmation" type="password" class="pass-inputs form-control">
                               <span class="ti toggle-passwords ti-eye-off"></span>
                           </div>
                           @error('password_confirmation')
                               <div class="text-danger">{{ $message }}</div>
                           @enderror
                       </div>

                       <div class="mb-3">
                           <button type="submit" class="btn btn-primary w-100">Change Password</button>
                       </div>
                       <div class="mb-3 text-center">
                           <h6>Return to <a href="login.html" class="text-purple link-hover"> Login</a></h6>
                       </div>
                       @include('auth.include.footer')
                   </div>
               </form>
           </div>
           <!-- Right Side Image -->
           <div class="auth-right"></div>
       </div>
   @endsection
