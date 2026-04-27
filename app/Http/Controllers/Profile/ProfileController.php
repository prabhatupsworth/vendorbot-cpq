<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    // update profile
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        // ✅ Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:800',
        ]);

        // ✅ Handle Image Upload
        if ($request->hasFile('profile_image')) {

            // delete old image
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // store new image
            $imagePath = $request->file('profile_image')->store('users', 'public');

            $user->profile_image = $imagePath;
        }

        // ✅ Update user data
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully');
    }
    public function security()
    {
        return view('profile.security');
    }
}
