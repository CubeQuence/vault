<?php

declare(strict_types=1);

namespace CQ\Vault;

final class Client extends BaseClient
{
    public function get(string $path): object
    {
        $path = $this->buildPath(path: $path);

        return $this->send(
            method: 'GET',
            path: $path
        );
    }

    public function list(string $path): object
    {
        $path = $this->buildPath(path: $path);

        return $this->send(
            method: 'LIST',
            path: $path
        );
    }

    public function post(string $path, array $body): object
    {
        $path = $this->buildPath(path: $path);

        return $this->send(
            method: 'POST',
            path: $path,
            body: $body
        );
    }

    public function put(string $path, array $body): object
    {
        $path = $this->buildPath(path: $path);

        return $this->send(
            method: 'PUT',
            path: $path,
            body: $body
        );
    }

    public function delete(string $path): object
    {
        $path = $this->buildPath(path: $path);

        return $this->send(
            method: 'DELETE',
            path: $path
        );
    }
}
