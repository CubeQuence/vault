<?php

declare(strict_types=1);

namespace Vault\Auth\Provider;

use Psr\Http\Client\ClientExceptionInterface;
use Vault\Auth\AuthProvider;

final class AppRole extends AuthProvider
{
    public function __construct(
        private string $roleId,
        private string $secretId,
        private string $name = 'approle'
    ) {
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function getToken(): string
    {
        $response = $this->client->write(
            path: '/auth/' . $this->name . '/login',
            body: [
                'role_id' => $this->roleId,
                'secret_id' => $this->secretId,
            ]
        );

        var_dump($response);

        // TODO: return response->token

        return '123';
    }
}
