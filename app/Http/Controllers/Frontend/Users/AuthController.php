<?php

namespace App\Http\Controllers\Frontend\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class AuthController extends Controller
{
    protected $views = [
        'login' => 'auth.login',
        'register' => 'auth.register',
    ];

    protected $auth;
    protected $hash;

    public function __construct(AuthFactory $auth, HasherContract $hash)
    {
        $this->auth = $auth;
        $this->hash = $hash;
    }

    public function showLoginForm()
    {
        return view($this->views['login']);
    }

    public function showRegisterForm()
    {
        return view($this->views['register']);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($this->auth->guard()->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('ucp.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
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
        $user->assignRole('user');

        $this->auth->guard()->login($user);

        return redirect()->route('ucp.dashboard');
    }

    public function logout(Request $request)
    {
        $this->auth->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
