<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Http\Resources\RoleResource;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminRoleController extends Controller
{
    public function index()
    {
        try {
            $roles = Role::with('permissions')->where('name', '!=', 'Super Admin')->get();
            
            return view('admin.roles.index', [
                'roles' => RoleResource::collection($roles)
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching roles: ' . $e->getMessage());
            return view('admin.roles.index', ['roles' => collect([])]);
        }
    }

    public function create()
    {
        try {
            $permissions = Permission::with('permissionProperty')->get();
            
            return view('admin.roles.create', [
                'permissions' => PermissionResource::collection($permissions)
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading create role page: ' . $e->getMessage());
            return view('admin.roles.create', ['permissions' => collect([])]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'type' => 'required|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        try {
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'web',
                'editable' => true,
            ]);

            // Check if roleProperty relationship exists
            if (method_exists($role, 'roleProperty')) {
                $role->roleProperty()->create([
                    'type' => $request->type,
                ]);
            }

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            return redirect()->route('admin.roles.index')->with('success', 'Role created successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating role: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create role. Please try again.');
        }
    }

    public function show(Role $role)
    {
        try {
            $role = $role->load('permissions.permissionProperty', 'roleProperty');
            
            return view('admin.roles.show', [
                'role' => new RoleResource($role)
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing role: ' . $e->getMessage());
            return redirect()->route('admin.roles.index')->with('error', 'Role not found.');
        }
    }

    public function edit(Role $role)
    {
        try {
            $role = $role->load('permissions.permissionProperty', 'roleProperty');
            $permissions = Permission::with('permissionProperty')->get();

            return view('admin.roles.edit', [
                'role' => new RoleResource($role),
                'permissions' => PermissionResource::collection($permissions)
            ]);
        } catch (\Exception $e) {
            Log::error('Error editing role: ' . $e->getMessage());
            return redirect()->route('admin.roles.index')->with('error', 'Role not found.');
        }
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'type' => 'required|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        try {
            $role->update([
                'name' => $request->name,
                'updated_at' => now(),
            ]);

            // Check if roleProperty relationship exists
            if (method_exists($role, 'roleProperty') && $role->roleProperty) {
                $role->roleProperty()->update([
                    'type' => $request->type,
                ]);
            }

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating role: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update role. Please try again.');
        }
    }

    public function destroy(Role $role)
    {
        try {
            if ($role->name === 'Super Admin') {
                return back()->with('error', 'Cannot delete Super Admin role.');
            }

            $role->delete();
            return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting role: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete role. Please try again.');
        }
    }
}