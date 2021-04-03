<?php

declare(strict_types=1);

namespace Vault;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Vault\Auth\AuthProvider;
use Vault\Exceptions\AuthenticationException;
use Vault\Exceptions\RequestException;
use Vault\Models\Token;

abstract class BaseClient
{
    private Token $token;

    public function __construct(
        private AuthProvider $authProvider,
        private string $baseUri,
        private string $version = 'v1'
    ) {
        $authProvider->setClient(client: $this);
        $this->authenticate();
    }

    /**
     * Inject version into path
     */
    protected function buildPath(string $path): string
    {
        return sprintf('/%s%s', $this->version, $path);
    }

    // ********** //

    private function authenticate(): void
    {
        $token = $this->authProvider->getToken();

        if (! $token) {
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
            creationTtl: $response?->data?->creation_time
        );
    }

    // ********** //

    /**
     * Send request to API
     */
    protected function send(
        string $method,
        string $path,
        array | null $body = null
    ): object {
        try {
            return $this->send2(
                method: $method,
                path: $path,
                body: $body
            );
        } catch (RequestException $error) {
            // Re-authenticate if 403 and token is expired
            if (
                $error->getCode() === 403 &&
                $this->token->isExpired()
            ) {
                $this->authenticate();

                return $this->send2(
                    method: $method,
                    path: $path,
                    body: $body
                );
            }

            throw $error;
        }
    }

    private function send2(// TODO: better name
        string $method,
        string $path,
        array | null $body = null
    ): object {
        $client = new Client([
            'base_uri' => $this->baseUri,
            'timeout' => 2.0,
        ]);

        $query = null;
        $headers = [
            'User-Agent' => 'VaultPHP/1.0.0',
            'X-Vault-Token' => $this->token ?
                $this->token->getToken() : null,
        ];

        if (strpos($path, '?') !== false) {
            [$path, $query] = explode('?', $path, 2);
        }

        try {
            $response = $client->request($method, $path, [
                'headers' => $headers,
                'query' => $query,
                'json' => $body,
            ]);
        } catch (TransferException $error) {
            throw new RequestException(
                message: $error->getMessage(),
                code: $error->getCode(),
                previous: $error
            );
        }

        return json_decode($response->getBody()->getContents());
    }
}