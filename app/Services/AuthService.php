<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class AuthService
{
    /**
     * Authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Hasher instance for password encryption.
     *
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hash;

    /**
     * AuthService constructor.
     *
     * @param AuthFactory $auth Authentication factory instance
     * @param HasherContract $hash Hasher instance for password hashing
     */
    public function __construct(AuthFactory $auth, HasherContract $hash)
    {
        $this->auth = $auth;
        $this->hash = $hash;
    }

    /**
     * Attempt to log in a user with the given credentials.
     *
     * @param array $credentials An array containing 'email' and 'password'
     * @return bool Returns true if login was successful, false otherwise
     */
    public function attemptLogin(array $credentials): bool
    {
        return $this->auth->guard()->attempt($credentials);
    }

    /**
     * Register a new user and log them in.
     *
     * @param array $validated Validated user data containing 'name', 'email', and 'password'
     * @return User Returns the created User instance
     */
    public function registerUser(array $validated): User
    {
        $user = new User([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $this->hash->make($validated['password']),
        ]);

        $user->save();

        if (method_exists($user, 'assignRole')) {
            $user->assignRole('user');
        }

        $this->auth->guard()->login($user);

        return $user;
    }

    /**
     * Log out the currently authenticated user.
     *
     * @return void
     */
    public function logout(): void
    {
        $this->auth->guard()->logout();
    }
}
