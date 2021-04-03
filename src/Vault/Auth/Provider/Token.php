<?php

declare(strict_types=1);

namespace Vault\Auth\Provider;

use Vault\Auth\AuthProvider;

final class Token extends AuthProvider
{
    public function __construct(
        private string $token
    ) {
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
