<?php

declare(strict_types=1);

namespace CQ\Vault\Engines;

use CQ\Vault\Client;

final class KV1
{
    public function __construct(
        private Client $client,
        private string $path = 'kv'
    ) {
    }

    public function read(string $path): object
    {
        $response = $this->client->get("/{$this->path}/{$path}");

        return $response->data;
    }

    public function list(): array
    {
        $response = $this->client->list("/{$this->path}");

        return $response->data->keys;
    }

    // Create and set data
    public function create(string $path, array $data): void
    {
        $this->client->post("/{$this->path}/{$path}", $data);
    }

    // Set data
    public function update(string $path, array $data): void
    {
        $this->client->put("/{$this->path}/{$path}", $data);
    }

    public function delete(string $path): void
    {
        $this->client->delete("/{$this->path}/{$path}");
    }
}
