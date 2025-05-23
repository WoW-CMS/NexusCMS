<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use GameCrypto\WoWCrypto;
use App\Models\User;
use App\Exceptions\UserNotFoundException;
use App\Traits\ConnectsToExternalDatabase;

class UserController extends Controller
{
    use ConnectsToExternalDatabase;

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
            throw new UserNotFoundException('User not found', 404);
        }

        // TODO: Remove this line when you have a real implementation
        // at the moment we are just returning a dummy user
        $user->coins = 0;

        return view($this->views['index'], compact('user'));
    }

    public function gameAccount()
    {
        $user = $this->auth->guard()->user();

        $gameAccounts = [
            [
                'username' => 'exampleuser',
                'created_at' => '2023-09-15',
                'last_login' => '2023-09-15',
                'status' => 'active',
                'coins' => 0,
                'characters' => [
                    [
                        'name' => 'Character 1',
                        'level' => 10,
                        'class' => 'Warrior',
                        'last_login' => '2023-09-15',
                        'status' => 'active',
                        'coins' => 0,
                    ],
                ],
            ],
        ];

        if (!$user) {
            throw new UserNotFoundException('User not found', 404);
        }

        return view('ucp.gameaccount', compact('user', 'gameAccounts'));
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

    public function createGameAccount(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:32',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6|max:32',
            'realm_id' => 'required|integer|exists:realms,id',
        ]);

        // Obtener el realm y su configuración de base de datos Auth
        $realm = \App\Models\Realm::findOrFail($request->realm_id);
        $authConfig = json_decode($realm->auth_database, true);

        // Conectar a la base de datos Auth
        $authDb = $this->connectToExternalDatabase($authConfig, 'auth');

        // Generar la contraseña usando WoWCrypto
        $username = strtoupper($request->username);
        $password = strtoupper($request->password);
        $sha_pass_hash = WoWCrypto::encryppt($password, $username); // TODO: Implement this method (see WoWCrypto class)

        // Insertar el usuario en la tabla account
        $authDb->table('account')->insert([
            'username' => $request->username,
            'email' => $request->email,
            'sha_pass_hash' => $sha_pass_hash,
            'expansion' => $realm->expansion ?? 2,
            'joindate' => now(),
        ]);

        // Aquí puedes enlazar la cuenta con el usuario web si lo necesitas

        return redirect()->back()->with('success', 'Cuenta de juego creada correctamente.');
    }
}
