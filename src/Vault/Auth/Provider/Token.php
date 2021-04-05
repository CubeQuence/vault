<?php

declare(strict_types=1);

namespace CQ\Vault\Auth\Provider;

use CQ\Vault\Auth\AuthProvider;

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
