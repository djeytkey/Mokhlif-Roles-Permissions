<?php

namespace BoukjijTarik\WooRoleManager\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use BoukjijTarik\WooRoleManager\Models\Permission;
use BoukjijTarik\WooRoleManager\Models\Role;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions.
     */
    public function index()
    {
        $permissions = Permission::with('roles')->paginate(20);
        return view('woorolemanager::permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new permission.
     */
    public function create()
    {
        $roles = Role::all();
        return view('woorolemanager::permissions.create', compact('roles'));
    }

    /**
     * Store a newly created permission.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'roles' => 'array'
        ]);

        $permission = Permission::create($validated);

        if (isset($validated['roles'])) {
            $permission->roles()->sync($validated['roles']);
        }

        return redirect()->route('woorolemanager.permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Display the specified permission.
     */
    public function show(Permission $permission)
    {
        $permission->load('roles');
        return view('woorolemanager::permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function edit(Permission $permission)
    {
        $roles = Role::all();
        $permission->load('roles');
        return view('woorolemanager::permissions.edit', compact('permission', 'roles'));
    }

    /**
     * Update the specified permission.
     */
    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'roles' => 'array'
        ]);

        $permission->update($validated);

        if (isset($validated['roles'])) {
            $permission->roles()->sync($validated['roles']);
        }

        return redirect()->route('woorolemanager.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified permission.
     */
    public function destroy(Permission $permission)
    {
        $permission->roles()->detach();
        $permission->delete();

        return redirect()->route('woorolemanager.permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }
} 