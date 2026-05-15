<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\PermissionRegistrar;

class UserController extends Controller
{
    // 🔹 List Users
    public function index(Request $request)
    {
        $roles = Role::all();

        // search value
        $search = $request->search;

        // base query
        $query = User::with('roles');

        // hide super admin for non-super-admin users
        if (!auth()->user()->hasRole('super_admin')) {

            $query->whereDoesntHave('roles', function ($q) {

                $q->where('name', 'super_admin');
            });
        }

        // search filter
        if (!empty($search)) {

            $query->where(function ($q) use ($search) {

                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // users list
        $users = $query->latest()->get();

        // AJAX response
        if ($request->ajax()) {

            $html = view(
                'users.partials.table',
                compact('users')
            )->render();

            return response()->json([
                'html' => $html
            ]);
        }

        // normal page load
        return view(
            'users.index',
            compact('users', 'roles')
        );
    }
    // 🔹 Show Create Form
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    // 🔹 Store User
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'status' => 'required|in:0,1',
            'role' => 'required',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:800',
        ]);

        // ✅ upload image
        $imagePath = null;

        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('users', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => $request->status,
            'profile_image' => $imagePath,
        ]);

        // ✅ assign role
        $user->assignRole($request->role);

        return redirect('/users')->with('success', 'User created');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->getRoleNames()->first(),
            'status' => $user->status,
            'profile_image_url' => $user->profile_image
                ? asset('storage/' . $user->profile_image)
                : null,
        ]);
    }

    public function update(Request $request, $id)
    {

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'status' => 'required|in:0,1',
            'role' => 'required',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:800',
        ]);

        // ✅ handle image update
        if ($request->hasFile('profile_image')) {

            // delete old image
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $user->profile_image = $request->file('profile_image')->store('users', 'public');
        }

        // ✅ update user
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        // ✅ sync role
        $user->syncRoles([$request->role]);

        return redirect()->back()->with('success', 'User updated');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // 🔒 Optional: prevent deleting yourself
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete yourself.');
        }

        // 🧹 Delete profile image (if exists)
        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // 🧹 Remove roles & permissions relations (Spatie)
        $user->syncRoles([]); // detaches roles
        // (permissions via roles will be handled automatically)

        // 🗑️ Delete user
        $user->delete();

        // 🔄 Clear permission cache (safe)
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
