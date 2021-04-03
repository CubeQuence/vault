<?php

declare(strict_types=1);

namespace Vault\Auth;

use Vault\Client;

abstract class AuthProvider
{
    protected Client $client;

    /**
     * Set client instance for auth types that
     * need to exchange credentials for a token
     */
    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    /**
     * Returns token for further interactions with Vault.
     */
    abstract public function getToken(): string;
}
