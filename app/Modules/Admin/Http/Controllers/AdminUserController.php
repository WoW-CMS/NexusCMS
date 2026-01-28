<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(15);
        return view('admin::users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('admin::users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'roles' => ['array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = $validated['password'];
        $user->save();

        $roles = $validated['roles'] ?? [];
        if (!in_array('Admin', $roles, true)) {
            $roles[] = 'Admin';
        }
        $user->syncRoles($roles);

        return redirect()->route('admin.users.index')->with('success', 'Administrador creado');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        $assigned = $user->roles()->pluck('name')->toArray();
        return view('admin::users.edit', compact('user', 'roles', 'assigned'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'roles' => ['array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
        }
        $user->save();

        $roles = $validated['roles'] ?? [];
        if (!in_array('Admin', $roles, true)) {
            $roles[] = 'Admin';
        }
        $user->syncRoles($roles);

        return redirect()->route('admin.users.index')->with('success', 'Administrador actualizado');
    }

    public function destroy(User $user)
    {
        if ($user->hasRole('Admin')) {
            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'Administrador eliminado');
        }
        return redirect()->route('admin.users.index')->with('error', 'El usuario no es administrador');
    }
}

