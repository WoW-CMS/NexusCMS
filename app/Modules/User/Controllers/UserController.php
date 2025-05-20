<?php

namespace App\Modules\User\Controllers;

class UserController extends BaseController
{
    protected $model = 'user';
    protected $auth;
    protected $hash;

    public function __construct(Auth $auth, Hash $hash)
    {
        $this->auth = $auth;
        $this->hash = $hash;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($this->auth->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = new User([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $this->hash->make($validated['password']),
        ]);
        $user->save();

        $this->auth->login($user);

        return redirect('/');
    }

    public function logout(Request $request)
    {
        $this->auth->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}