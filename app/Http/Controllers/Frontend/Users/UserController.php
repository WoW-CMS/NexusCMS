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
        'gameAccount' => 'ucp.gameaccount',
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

        foreach ($gameLinked as $game) {
            $realm    = Realm::findOrFail($game->realm_id);
            $external = $this->connectToExternalDatabase(
                json_decode($realm->auth_database, true)
            );
            
            if (empty($external)) {
                return [];
            }


            $acc = $external->table('account')->where('id', $game->target_id)->first();

            $account[] = [
                'username'   => $game->username,
                'email'      => $acc->email,
                'created_at' => $acc->joindate,
                'lastip'     => $acc->last_ip,
                'status'     => $acc->locked,
                'active'     => $acc->online,
                'expansion'  => $acc->expansion,
            ];
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
        // Generate password using WoWCrypto
        $username = strtoupper($request->username);
        $email    = strtoupper($request->email);
        $password = strtoupper($request->password);

        // Obtain realm configuration
        $realm = Realm::findOrFail($request->realm);
        $al    = new AccountLibrary($realm);

        try {
            $account = $al->createNewAccount($username, $password, $email, true);

            $gameAccountMatch = preg_match('/game account ([^ ]+)/i', $account, $gameAccountMatch);
            $gameAccount = $gameAccountMatch[1] ?? null;

            if ($gameAccount) {
                $gameAccount = explode('#', $gameAccount)[0];
            }
            
            $existingAccount = AccountLinked::where('target_id', $gameAccount)->first();

            if ($existingAccount) {
                dump($existingAccount);
                die();
                return redirect()->back()->with('error', 'Game account is already linked to this user.');
            }

            $accountLinked = new AccountLinked();
            $accountLinked->username  = $username;
            $accountLinked->user_id   = $request->user()->id;
            $accountLinked->realm_id  = $request->realm_id;
            $accountLinked->target_id = $gameAccount;
            $accountLinked->save();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating game account: ' . $e->getMessage());
        }

        return redirect()->route('ucp.gameaccount')->with('success', 'Game account created successfully.');
    }
}
