<?php

declare(strict_types=1);

namespace CQ\Vault;

use CQ\Request\Request;
use CQ\Vault\Exceptions\AuthenticationException;

final class Vault
{
    public function __construct(
        private string $token,
        private string $baseUri = 'http://127.0.0.1:8200',
        private string $version = 'v1'
    ) {
        // Test if token is valid
        try {
            $this->get('/auth/token/lookup-self');
        } catch (\Throwable) {
            throw new AuthenticationException(
                message: 'Invalid Token'
            );
        }
    }

    public function get(string $path): object
    {
        return $this->send(method: 'GET', path: $path);
    }

    public function list(string $path): object
    {
        return $this->send(method: 'LIST', path: $path);
    }

    public function post(string $path, array $body): object
    {
        return $this->send(method: 'POST', path: $path, body: $body);
    }

    public function put(string $path, array $body): object
    {
        return $this->send(method: 'PUT', path: $path, body: $body);
    }

    public function delete(string $path): object
    {
        return $this->send(method: 'DELETE', path: $path);
    }

    /**
     * Inject version into path
     */
    private function buildPath(string $path): string
    {
        if (!str_contains($path, '/')) {
            $path = '/' . $path;
        }

        return "{$this->baseUri}/{$this->version}{$path}";
    }

    private function send(
        string $method,
        string $path,
        array | null $body = null
    ): object {
        return Request::send(
            method: $method,
            path: $this->buildPath($path),
            json: $body,
            headers: ['X-Vault-Token' => $this->token]
        );
    }
}
