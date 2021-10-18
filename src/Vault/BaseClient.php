<?php

declare(strict_types=1);

namespace CQ\Vault;

use CQ\Request\Request;
use CQ\Request\Exceptions\BadResponseException;
use CQ\Vault\Auth\AuthProvider;
use CQ\Vault\Exceptions\AuthenticationException;
use CQ\Vault\Models\Token;

abstract class BaseClient
{
    private Token $token;

    public function __construct(
        private AuthProvider $authProvider,
        private string $baseUri,
        private string $version = 'v1'
    ) {
        $authProvider->setClient(client: $this);
        $this->token = new Token();
        $this->authenticate();
    }

    /**
     * Inject version into path
     */
    protected function buildPath(string $path): string
    {
        return "{$this->baseUri}/{$this->version}{$path}";
    }

    /**
     * Get new token using credentials from authProvider
     */
    private function authenticate(): void
    {
        $token = $this->authProvider->getToken();

        if (!$token) {
            throw new AuthenticationException(
                message: 'Cannot authenticate'
            );
        }

        // Set temporary token
        $this->token = new Token(
            token: $token
        );

        // Get token info
        $path = $this->buildPath(path: '/auth/token/lookup-self');
        $response = $this->send(
            method: 'GET',
            path: $path
        );

        // Set updated token
        $this->token = new Token(
            token: $token,
            creationTime: $response?->data?->creation_time,
            creationTtl: $response?->data?->creation_ttl
        );
    }

    /**
     * Test auth for API
     */
    protected function send(
        string $method,
        string $path,
        array | null $body = null
    ): object {
        try {
            return Request::send(
                method: $method,
                path: $path,
                json: $body,
                headers: [
                    'X-Vault-Token' => $this->token->getToken(),
                ]
            );
        } catch (BadResponseException $error) {
            // Try to re-authenticate if 403 and token is expired
            if (
                $error->getCode() === 403 &&
                $this->token->isExpired()
            ) {
                $this->authenticate();

                return Request::send(
                    method: $method,
                    path: $path,
                    json: $body,
                    headers: [
                        'X-Vault-Token' => $this->token->getToken(),
                    ]
                );
            }

            throw $error;
        }
    }
}
