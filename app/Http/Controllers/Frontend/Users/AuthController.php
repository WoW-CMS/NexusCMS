<?php

namespace App\Http\Controllers\Frontend\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;

/**
 * Handles user authentication and registration workflows.
 *
 * This controller provides endpoints for displaying authentication forms,
 * logging in users, registering new accounts, and handling logout actions.
 * It delegates core authentication logic to the {@see AuthService}.
 *
 * @package App\Http\Controllers\Frontend\Users
 * @since 1.0.0
 * @api
 */
class AuthController extends Controller
{
    /**
     * The views associated with authentication pages.
     *
     * @var array<string, string> Associative array of view names
     * @since 1.0.0
     */
    protected $views = [
        'login'    => 'auth.login',
        'register' => 'auth.register',
    ];

    /**
     * The authentication service instance.
     *
     * @var AuthService
     * @since 1.0.0
     */
    protected AuthService $authService;

    /**
     * Create a new AuthController instance.
     *
     * @param AuthService $authService The authentication service dependency
     * @since 1.0.0
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Display the login form.
     *
     * Renders the login page view used by users to provide their
     * credentials before authentication.
     *
     * @return \Illuminate\View\View The rendered login form view
     * @since 1.0.0
     */
    public function showLoginForm()
    {
        return view($this->views['login']);
    }

    /**
     * Display the registration form.
     *
     * Renders the registration page view where users can submit
     * details to create a new account.
     *
     * @return \Illuminate\View\View The rendered registration form view
     * @since 1.0.0
     */
    public function showRegisterForm()
    {
        return view($this->views['register']);
    }

    /**
     * Attempt to log in the user with the provided credentials.
     *
     * Validates the request input for email and password, attempts authentication
     * via the {@see AuthService}, and regenerates the session upon success.
     *
     * @param Request $request The incoming HTTP request containing credentials
     * @return \Illuminate\Http\RedirectResponse Redirects to dashboard on success
     *                                           or back with errors on failure
     * @throws \Illuminate\Validation\ValidationException When validation fails
     * @since 1.0.0
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($this->authService->attemptLogin($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('ucp.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Register a new user account.
     *
     * Validates user input including name, email, and password,
     * then delegates account creation to the {@see AuthService}.
     *
     * @param Request $request The incoming HTTP request containing registration data
     * @return \Illuminate\Http\RedirectResponse Redirects to the dashboard after successful registration
     * @throws \Illuminate\Validation\ValidationException When validation fails
     * @since 1.0.0
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $this->authService->registerUser($validated);

        return redirect()->route('ucp.dashboard');
    }

    /**
     * Log out the currently authenticated user.
     *
     * Terminates the user session, regenerates the CSRF token,
     * and redirects to the home page.
     *
     * @param Request $request The current HTTP request
     * @return \Illuminate\Http\RedirectResponse Redirects to the home page
     * @since 1.0.0
     */
    public function logout(Request $request)
    {
        $this->authService->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
