<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use App\Models\User;

class UserController extends Controller
{
    protected $views = [
        'index' => 'ucp.index',
    ];

    protected $auth;
    protected $hash;

    public function __construct(AuthFactory $auth, HasherContract $hash)
    {
        $this->auth = $auth;
        $this->hash = $hash;
    }

    public function show()
    {
        $user = $this->auth->guard()->user();

        if (!$user) {
            return throw new \Exception('User not found', 404);
        }

        // TODO: Remove this line when you have a real implementation
        // at the moment we are just returning a dummy user
        $user->coins = 0;

        return view($this->views['index'], compact('user'));
    }

    public function gameAccount()
    {
        $user = $this->auth->guard()->user();

        if (!$user) {
            return throw new \Exception('User not found', 404);
        }

        return view('ucp.gameaccount', compact('user'));
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

        if ($this->auth->guard()->attempt($credentials)) {
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

        $this->auth->guard()->login($user);

        return redirect('/');
    }

    public function logout(Request $request)
    {
        $this->auth->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
