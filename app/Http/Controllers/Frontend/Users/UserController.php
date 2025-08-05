<?php

namespace App\Http\Controllers\Frontend\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use App\Exceptions\UserNotFoundException;
use App\Helpers\RealmHelper;
use App\Libraries\Auth\AccountLibrary;
use App\Models\AccountLinked;
use App\Models\Realm;
use App\Traits\ConnectsToExternalDatabase;

/**
 * User Controller for handling user-related actions in the frontend
 */
class UserController extends Controller
{
    use ConnectsToExternalDatabase;

    /**
     * View paths mapping
     *
     * @var array<string, string>
     */
    protected $views = [
        'index' => 'ucp.index',
        'create' => 'ucp.createAccount',
        'gameAccount' => 'ucp.gameAccount',
        'manage' => 'ucp.manageAccount',
    ];

    /**
     * @var AuthFactory
     */
    protected $auth;

    /**
     * @var HasherContract
     */
    protected $hash;

    /**
     * Constructor
     *
     * @param AuthFactory $auth
     * @param HasherContract $hash
     */
    public function __construct(AuthFactory $auth, HasherContract $hash)
    {
        $this->auth = $auth;
        $this->hash = $hash;
    }

    /**
     * Display user profile
     *
     * @throws UserNotFoundException
     * @return \Illuminate\View\View
     */
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

    /**
     * Show create account form
     *
     * @throws UserNotFoundException
     * @return \Illuminate\View\View
     */
    public function createAction()
    {
        $realms = RealmHelper::all();
        $user = $this->auth->guard()->user();

        if (!$user) {
            throw new UserNotFoundException('User not found', 404);
        }

        return view($this->views['create'], [
            'user' => $user,
            'realms' => $realms,
        ]);
    }

    /**
     * Show account management page
     *
     * @throws UserNotFoundException
     * @return \Illuminate\View\View
     */
    public function manage()
    {
        $user = $this->auth->guard()->user();
        $linkedAccount = AccountLinked::where('user_id', $user->id)->first();

        if (!$user) {
            throw new UserNotFoundException('User not found', 404);
        }

        return view($this->views['manage'], [
            'user' => $user,
            'account' => $linkedAccount ?? [],
        ]);
    }

    /**
     * Show game account page
     *
     * @throws UserNotFoundException
     * @return \Illuminate\View\View
     */
    public function gameAccount()
    {
        $user = $this->auth->guard()->user();

        if (!$user) {
            throw new UserNotFoundException('User not found', 404);
        }

        $gameAccounts = $this->getUserGameAccounts($user) ?? [];

        return view($this->views['gameAccount'], [
            'user'          => $user,
            'gameAccounts'  => $gameAccounts,
            'realm'         => Realm::all(),
        ]);
    }

    /**
     * Get user's game accounts
     *
     * @param mixed $user
     * @return array|null
     */
    private function getUserGameAccounts($user)
    {
        $gameLinked = AccountLinked::where('user_id', $user->id)->get();
        $account = [];
        $connectionErrors = false;

        foreach ($gameLinked as $game) {
            try {
                $realm = Realm::findOrFail($game->realm_id);
                $external = $this->connectToExternalDatabase(
                    json_decode($realm->auth_database, true)
                );
                
                if (empty($external)) {
                    // Registramos que hubo un error de conexión pero continuamos con otros reinos
                    $connectionErrors = true;
                    continue;
                }

                $acc = $external->table('account')->where('id', $game->target_id)->first();
                
                if (!$acc) {
                    $connectionErrors = true;
                    continue;
                }

                $characters = [];

                try {
                    if (!empty($realm->characters_database)) {
                        $charactersDb = $this->connectToExternalDatabase(
                            json_decode($realm->characters_database, true),
                            'characters'
                        );
                        
                        if ($charactersDb) {
                            $chars = $charactersDb->table('characters')
                                ->where('account', $game->target_id)
                                ->select(['guid', 'name', 'race', 'class', 'level', 'gender'])
                                ->get();
                                
                            if ($chars) {
                                $characters = $chars->toArray();
                            }
                        }
                    }
                } catch (\Exception $e) {
                    session()->flash('warning', 'Error al obtener personajes');
                }

                $account[] = [
                    'username'   => $game->username,
                    'email'      => $acc->email ?? 'No disponible',
                    'created_at' => $acc->joindate ?? now(),
                    'lastip'     => $acc->last_ip ?? '0.0.0.0',
                    'status'     => $acc->locked ?? 1,
                    'active'     => $acc->online ?? 0,
                    'expansion'  => $acc->expansion ?? 0,
                    'characters' => $characters,
                    'realm_name' => $realm->name,
                    'last_login' => $acc->last_login ?? 'Nunca',
                ];
            } catch (\Exception $e) {
                $connectionErrors = true;
                continue;
            }
        }

        // Si hubo errores de conexión pero pudimos obtener algunas cuentas
        if ($connectionErrors && !empty($account)) {
            session()->flash('warning', 'Se han encontrado problemas al conectar con algunos servidores. La información mostrada puede estar incompleta.');
        }
        // Si hubo errores de conexión y no pudimos obtener ninguna cuenta
        else if ($connectionErrors && empty($account)) {
            session()->flash('error', 'No se ha podido conectar con los servidores de juego. Por favor, inténtalo de nuevo más tarde.');
        }

        return $account;
    }

    /**
     * Create a new game account
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createGameAccount(Request $request)
    {
        // Validate the request
        $request->validate([
            'username' => 'required|string|min:3|max:12|alpha_num',
            'password' => 'required|string|min:6|confirmed',
            'email' => 'required|email|max:255',
            'realm' => 'required|exists:realms,id',
            'terms' => 'required|accepted'
        ], [
            'username.required' => 'Username is required.',
            'username.min' => 'Username must be at least 3 characters.',
            'username.max' => 'Username cannot be more than 12 characters.',
            'username.alpha_num' => 'Username can only contain letters and numbers.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email format.',
            'realm.required' => 'You must select a realm.',
            'realm.exists' => 'Selected realm is invalid.',
            'terms.required' => 'You must accept the terms and conditions.',
            'terms.accepted' => 'You must accept the terms and conditions.'
        ]);

        // Generate password using WoWCrypto
        $username = strtoupper($request->username);
        $email    = strtoupper($request->email);
        $password = strtoupper($request->password);

        try {
            // Obtain realm configuration
            $realm = Realm::findOrFail($request->realm);
            
            // Check if realm database configuration exists
            if (empty($realm->auth_database)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'External database configuration not found.');
            }

            $al = new AccountLibrary($realm);
            $external = $this->connectToExternalDatabase(
                json_decode($realm->auth_database, true)
            );

            // Check database connection
            if (empty($external)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Error connecting to external database. Please try again.');
            }

            // Check if username already exists in the external database
            $existingUsername = $external->table('account')->where('username', $username)->first();
            if ($existingUsername) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['username' => 'This username is already in use.']);
            }

            // Check if email already exists in the external database
            $existingEmail = $external->table('account')->where('email', $email)->first();
            if ($existingEmail) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['email' => 'This email is already registered.']);
            }

            // Create the account
            $al->createNewAccount($username, $password, $email, true);
            $acc = $external->table('account')->where('email', $email)->first();

            if (!$acc) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Error creating game account. Please try again.');
            }

            // Check if account is already linked to another user
            $existingAccount = AccountLinked::where('target_id', $acc->id)->first();
            if ($existingAccount) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'This game account is already linked to another user.');
            }

            // Check if user already has an account linked to this realm
            $userRealmAccount = AccountLinked::where('user_id', $request->user()->id)
                ->where('realm_id', $realm->id)
                ->first();
            if ($userRealmAccount) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'You already have an account linked to this realm.');
            }

            // Link the account
            $accountLinked = new AccountLinked();
            $accountLinked->username = $username;
            $accountLinked->user_id = $request->user()->id;
            $accountLinked->realm_id = $realm->id;
            $accountLinked->target_id = $acc->id;
            $accountLinked->save();

            return redirect()->route($this->views['gameaccount'])
                ->with('success', 'Cuenta de juego creada y vinculada exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la cuenta de juego: ' . $e->getMessage());
        }
    }
}
