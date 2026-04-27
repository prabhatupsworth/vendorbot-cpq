<?php

namespace App\Http\Controllers\Admin\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }
    public function getRoles()
    {
        $roles = Role::latest()->get();

        $data = [];

        foreach ($roles as $role) {
            $data[] = [
                'id' => $role->id,
                'roles_name' => $role->name,
                'created' => $role->created_at->format('d M Y, h:i A'),
            ];
        }

        return response()->json([
            'data' => $data
        ]);
    }
    public function create()
    {
        $permissions = Permission::all()->groupBy('module');
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name'
        ]);

        Role::create([
            'name' => $request->name
        ]);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role created successfully');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all()->groupBy('module');
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id
        ]);

        $role = Role::findOrFail($id);

        $role->update([
            'name' => $request->name
        ]);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role updated successfully');
    }


    public function permissions($id)
    {
        $role = Role::findOrFail($id);

        $permissions = Permission::all();

        // group: user.create → user
        $modules = $permissions->groupBy(function ($item) {
            return explode('.', $item->name)[0];
        });

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.permissions', compact('role', 'modules', 'rolePermissions'));
    }

    public function permissionData($id)
    {
        $role = Role::findOrFail($id);

        $permissions = Permission::select('name', 'module')->get();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        $modules = [];

        foreach ($permissions as $perm) {

            if (!str_contains($perm->name, '.')) continue;

            [$module, $action] = explode('.', $perm->name);

            if (!isset($modules[$module])) {
                $modules[$module] = [
                    'module' => ucfirst($module),
                    'module_key' => $module,
                    'actions' => []
                ];
            }

            $modules[$module]['actions'][$action] = in_array($perm->name, $rolePermissions);
        }

        return response()->json(array_values($modules));
    }


    public function togglePermission(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $permission = Permission::where('name', $request->permission)->first();

        if (!$permission) {
            return response()->json(['status' => false]);
        }

        if ($request->checked) {

            // ✅ assign
            $role->givePermissionTo($permission->name);
        } else {

            // ✅ revoke (now works perfectly)
            $role->revokePermissionTo($permission->name);
        }

        return response()->json(['status' => true]);
    }
}
