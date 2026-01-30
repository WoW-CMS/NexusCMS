<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('name')->paginate(15);
        return view('admin::users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('admin::users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:users,name'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'dp' => ['nullable', 'numeric'],
            'vp' => ['nullable', 'numeric'],
            'roles.*' => ['integer', 'exists:roles,id'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'dp' => $validated['dp'] ?? 0,
            'vp' => $validated['vp'] ?? 0,
        ]);

        if (!empty($validated['roles'])) {
            $roles = \Spatie\Permission\Models\Role::whereIn('id', $validated['roles'])
                ->pluck('name');
            $user->syncRoles($roles);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        $assigned = $user->roles()->pluck('name')->toArray();
        return view('admin::users.edit', compact('user', 'roles', 'assigned'));
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('users', 'name')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'dp' => ['nullable', 'numeric'],
            'vp' => ['nullable', 'numeric'],
            'roles' => ['array'],
            'roles.*' => ['integer', 'exists:roles,id'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->dp = $validated['dp'] ?? 0;
        $user->vp = $validated['vp'] ?? 0;
        $user->save();

        if (!empty($validated['roles'])) {
            $roles = \Spatie\Permission\Models\Role::whereIn('id', $validated['roles'])
                ->pluck('name');
            $user->syncRoles($roles);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->hasRole('Admin')) {
            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
        }
        return redirect()->route('admin.users.index')->with('error', 'The user is not an administrator');
    }
}

