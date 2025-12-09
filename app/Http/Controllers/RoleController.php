<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use App\Modules\User\Enums\RoleType;
use App\Traits\TracksAdminActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class RoleController extends Controller
{
    use TracksAdminActivity;
    public function index(): Response
    {
        Gate::authorize('viewAny', Role::class);

        $roles = Role::where('name', '!=', 'Super Admin')->get();

        return Inertia::render('Role/Index', [
            'roles' => $roles,
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', Role::class);

        $permissions = Permission::with(['permissionProperty'])->get();

        return Inertia::render('Role/Create', [
            'permissions' => PermissionResource::collection($permissions)->resolve(),
        ]);
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
            'editable' => true,
        ]);

        $role->roleProperty()->create([
            'type' => $request->type,
        ]);

        $permissions = collect($request->permissions)->pluck('name')->toArray();
        $role->syncPermissions($permissions);

        // Log the role creation activity
        $this->logRoleCreation($role->id, $role->name, $permissions);

        return redirect()->route('roles.index');
    }

    public function show(Role $role): Response
    {
        Gate::authorize('view', $role);

        $role = $role->load('permissions.permissionProperty', 'roleProperty');
        return Inertia::render('Role/Show', [
            'role' => RoleResource::make($role),
        ]);
    }

    public function edit(Role $role): Response
    {
        Gate::authorize('update', $role);

        $role = $role->load('permissions.permissionProperty', 'roleProperty');

        $permissions = Permission::with('permissionProperty')->get();

        return Inertia::render('Role/Edit', [
            'permissions' => PermissionResource::collection($permissions)->resolve(),
            'role' => RoleResource::make($role)->resolve(),
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        Gate::authorize('update', $role);

        $role->update([
            'name' => $request->name,
            'updated_at' => now(),
        ]);

        $role->roleProperty()->update([
            'type' => RoleType::from(strtolower($request->type)),
        ]);

        $permissions = collect($request->permissions)->pluck('name')->toArray();
        $role->syncPermissions($permissions);

        // Log the role update activity
        $this->logRoleUpdate($role->id, $role->name, $permissions);

        return redirect()->route('roles.index');
    }

    public function getFilteredData(Request $request)
    {
        Gate::authorize('viewAny', Role::class);

        $filters = $request->get('filters', []);
        $sorting = $request->get('sorting', []);

        $roles = Role::with(['roleProperty'])
            ->applyFilters($filters)
            ->applySorting($sorting)
            ->paginate(15);

        return RoleResource::collection($roles);
    }
}
