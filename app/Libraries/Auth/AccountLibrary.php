<?php

namespace App\Libraries\Auth;

use GameCrypto\SoapAccountCreator;

class AccountLibrary
{
    private SoapAccountCreator $soapCreator;
    private object $realm;

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
     * Creates a new account using either Battle.net or non-Battle.net authentication
     */
    public function createNewAccount(string $username, string $password, string $email, bool $isBnet = false): bool
    {
        try {
            if ($isBnet) {
                return $this->soapCreator->createAccountBnet($username, $password);
            }
            
            return $this->soapCreator->createAccount($username, $password, $email);
        } catch (\Exception $e) {
            throw new \Exception('Account creation failed: ' . $e->getMessage());
        }
    }
}
