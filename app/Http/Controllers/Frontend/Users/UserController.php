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

class UserController extends Controller
{
    use ConnectsToExternalDatabase;

    protected $views = [
        'index' => 'ucp.index',
        'create' => 'ucp.createAccount',
        'gameAccount' => 'ucp.gameaccount',
        'manage' => 'ucp.manageAccount',
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

    public function manage()
    {
        $user          = $this->auth->guard()->user();
        $linkedAccount = AccountLinked::where('user_id', $user->id)->first();

        if (!$user) {
            throw new UserNotFoundException('User not found', 404);
        }

        return view($this->views['manage'], [
            'user' => $user,
            'account' => $account ?? [],
        ]);
    }

    public function gameAccount()
    {
        $user = $this->auth->guard()->user();

        if (!$user) {
            throw new UserNotFoundException('User not found', 404);
        }

        $gameAccounts = $this->getUserGameAccounts($user) ?? [];

        return view('ucp.gameaccount', [
            'user' => $user,
            'gameAccounts' => $gameAccounts,
            'realm' => Realm::all(),
        ]);
    }

    public function battlePass()
    {
        $user = $this->auth->guard()->user();

        if (!$user) {
            throw new UserNotFoundException('User not found', 404);
        }

        return view('ucp.battlepass', compact('user'));
    }

    private function getUserGameAccounts($user)
    {
        $gameLinked = AccountLinked::where('user_id', $user->id)->get();

        foreach ($gameLinked as $game) {
            $realm = Realm::findOrFail($game->realm_id);
            $external = $this->connectToExternalDatabase(
                json_decode($realm->auth_database, true)
            );
            
            $acc = $external->table('account')->where('id', $game->target_id)->first();

            $account[] = [
                'username' => $game->username,
                'email' => $acc->email,
                'created_at' => $acc->joindate,
                'lastip' => $acc->last_ip,
                'status' => $acc->locked,
                'active' => $acc->online,
                'expansion' => $acc->expansion,
            ];
        }

        return $account;
    }

    public function createGameAccount(Request $request)
    {
        // Generar la contraseña usando WoWCrypto
        $username  = strtoupper($request->username);
        $email     = strtoupper($request->email);
        $password  = strtoupper($request->password);
        $realm     = $request->realm;

        // Obtain realm configuratio
        $realm = \App\Models\Realm::findOrFail($request->realm_id);
        $al    = new AccountLibrary($realm);

        try {
            $account = $al->createNewAccount($username, $password, $email, true);

            $gameAccountMatch = preg_match('/game account ([^ ]+)/i', $account, $gameAccountMatch);
            $gameAccount      = $gameAccountMatch[1] ?? null;

            if ($gameAccount) {
                $gameAccount = explode('#', $gameAccount)[0];
            }
            
            $existingAccount = AccountLinked::where('target_id', $gameAccount)->first();

            if ($existingAccount) {
                return redirect()->back()->with('error', 'La cuenta de juego ya esta vinculada con este usuario.');
            }

            $accountLinked = new AccountLinked();
            $accountLinked->user_id = $request->user()->id;
            $accountLinked->realm_id = $request->realm_id;
            $accountLinked->target_id = $gameAccount;
            $accountLinked->save();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear la cuenta de juego: ' . $e->getMessage());
        }

        
        return redirect()->back()->with('success', 'Cuenta de juego creada correctamente.');
    }
}
