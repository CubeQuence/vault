<?php

declare(strict_types=1);

namespace CQ\Vault\Auth\Provider;

use CQ\Vault\Auth\AuthProvider;

final class AppRole extends AuthProvider
{
    public function __construct(
        private string $roleId,
        private string $secretId,
        private string $name = 'approle'
    ) {
    }

    public function getToken(): string
    {
        $response = $this->client->post(
            path: '/auth/' . $this->name . '/login',
            body: [
                'role_id' => $this->roleId,
                'secret_id' => $this->secretId,
            ]
        );

        return $response?->auth?->client_token;
    }
}
