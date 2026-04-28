<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;


class AuthController extends Controller
{
    // 🔹 Show Login Page
    public function showLogin()
    {
        return view('auth.login');
    }

    // 🔹 Handle Login
    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials,$remember)) {
            $request->session()->regenerate();

            return redirect()->route('profile')->with('success', 'Logged in successfully');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials',
        ]);
    }

    public function showSignup()
    {
        return view('auth.signup');
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Account created successfully');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function showResetPassword(Request $request ,$token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email')
        ]);
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }


    public function resetPassword(Request $request)
    {

        try {
            // ✅ Validation
            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:6|confirmed',
            ]);

            // ✅ Attempt reset
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {

                    $user->password = Hash::make($password);
                    $user->setRememberToken(str()->random(60));
                    $user->save();

                    Auth::login($user); // optional
                }
            );

            // ✅ Success
            if ($status === Password::PASSWORD_RESET) {
                return redirect()
                    ->route('home')
                    ->with('success', 'Password reset successful');
            }

            // ❌ Known failure (invalid token, expired, etc.)
            return back()->withErrors([
                'email' => __($status)
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // ✅ Validation errors
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            // 🔥 Log error (VERY IMPORTANT)
            \Log::error('Password Reset Error: ' . $e->getMessage());

            return back()->withErrors([
                'error' => 'Something went wrong. Please try again.'
            ]);
        }
    }
    // 🔹 Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
