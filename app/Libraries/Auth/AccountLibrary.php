<?php

namespace App\Libraries\Auth;

use GameCrypto\SoapAccountCreator;

class AccountLibrary
{
    /**
     * The SoapAccountCreator instance for account creation.
     */
    private SoapAccountCreator $soapCreator;

    /**
     * The realm object containing console credentials.
     */
    private object $realm;

    /**
     * Constructor to initialize the AccountLibrary with realm credentials.
     *
     * @param object $realm The realm object containing console credentials.
     */
    public function __construct(object $realm)
    {
        $this->realm = $realm;
        $this->soapCreator = new SoapAccountCreator(
            $realm->console_hostname,
            $realm->console_port,
            $realm->console_username,
            $realm->console_password,
            $realm->console_urn,
            false
        );
    }

    /**
     * Creates a new account using either Battle.net or non-Battle.net authentication.
     *
     * @param string $username The username for the new account.
     * @param string $password The password for the new account.
     * @param string $email    The email address for the new account.
     * @param bool   $isBnet   Whether the account is for Battle.net authentication.
     *
     * @return bool True if the account was successfully created, false otherwise.
     *
     * @throws \Exception If the account creation fails.
     */
    public function createNewAccount(string $username, string $password, string $email, bool $isBnet = false): bool
    {
        try {
            // Validate username and password length
            if (strlen($username) < 3 || strlen($password) < 6) {
                throw new \Exception('Username must be at least 3 characters long and password must be at least 6 characters long.');
            }

            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception('Invalid email format.');
            }

            if ($isBnet) {
                // Create Battle.net account
                return $this->soapCreator->createAccountBnet($email, $password);
            }

            // Create non-Battle.net account
            return $this->soapCreator->createAccount($username, $password, $email);
        } catch (\Exception $e) {
            throw new \Exception('Account creation failed: ' . $e->getMessage());
        }
    }
}
