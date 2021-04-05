<?php

declare(strict_types=1);

namespace CQ\Vault;

final class Client extends BaseClient
{
    public function read(string $path): object
    {
        $path = $this->buildPath(path: $path);

        return $this->send(
            method: 'GET',
            path: $path
        );
    }

    public function keys(string $path): object
    {
        $path = $this->buildPath(path: $path);

        return $this->send(
            method: 'LIST',
            path: $path
        );
    }

    public function write(string $path, array $body): object
    {
        $path = $this->buildPath(path: $path);

        return $this->send(
            method: 'POST',
            path: $path,
            body: $body
        );
    }

    public function revoke(string $path): object
    {
        $path = $this->buildPath(path: $path);

        return $this->send(
            method: 'DELETE',
            path: $path
        );
    }
}
