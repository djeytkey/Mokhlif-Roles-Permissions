<?php

namespace BoukjijTarik\WooRoleManager\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use BoukjijTarik\WooRoleManager\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $userModelClass = config('auth.providers.users.model', \App\Models\User::class);
        $users = $userModelClass::with('roles')->paginate(20);
        return view('woorolemanager::users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        return view('woorolemanager::users.create', compact('roles'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $userModelClass = config('auth.providers.users.model', \App\Models\User::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'roles' => 'array'
        ]);

        $user = $userModelClass::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if (isset($validated['roles'])) {
            $user->roles()->sync($validated['roles']);
        }

        return redirect()->route('woorolemanager.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $userModelClass = config('auth.providers.users.model', \App\Models\User::class);
        $user = $userModelClass::with('roles')->findOrFail($id);
        return view('woorolemanager::users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $userModelClass = config('auth.providers.users.model', \App\Models\User::class);
        $user = $userModelClass::with('roles')->findOrFail($id);
        $roles = Role::all();
        return view('woorolemanager::users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, $id)
    {
        $userModelClass = config('auth.providers.users.model', \App\Models\User::class);
        $user = $userModelClass::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'roles' => 'array'
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (isset($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        if (isset($validated['roles'])) {
            $user->roles()->sync($validated['roles']);
        }

        return redirect()->route('woorolemanager.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy($id)
    {
        $userModelClass = config('auth.providers.users.model', \App\Models\User::class);
        $user = $userModelClass::findOrFail($id);
        
        $user->roles()->detach();
        $user->delete();

        return redirect()->route('woorolemanager.users.index')
            ->with('success', 'User deleted successfully.');
    }
} 