<?php

declare(strict_types=1);

namespace Vault\Auth;

use Vault\Client;

abstract class AuthProvider
{
    private Client $client;

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Returns clientToken for further interactions with Vault.
     */
    abstract public function getClientToken(): string;
}
