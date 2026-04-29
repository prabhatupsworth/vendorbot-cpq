   @extends('layouts.auth')
   @section('title', 'Reset Password')
   @section('content')
       <div class="d-flex flex-wrap w-100 vh-100 overflow-hidden account-bg-04">
           <div class="d-flex align-items-center justify-content-center flex-wrap vh-100 overflow-auto p-4 w-50 bg-backdrop">

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
       </div>
   @endsection
