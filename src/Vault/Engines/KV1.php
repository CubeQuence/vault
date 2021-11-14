<?php

declare(strict_types=1);

namespace CQ\Vault\Engines;

use CQ\Vault\Vault;

final class KV1
{
    public function __construct(
        private Vault $vault,
        private string $path = 'kv'
    ) {
    }

    public function read(string $path): object
    {
        $response = $this->vault->get("/{$this->path}/{$path}");

        return $response->data;
    }

    public function list(): array
    {
        $response = $this->vault->list("/{$this->path}");

        return $response->data->keys;
    }

    // Create and set data
    public function create(string $path, array $data): void
    {
        $this->vault->post("/{$this->path}/{$path}", $data);
    }

    // Set data
    public function update(string $path, array $data): void
    {
        $this->vault->put("/{$this->path}/{$path}", $data);
    }

    public function delete(string $path): void
    {
        $this->vault->delete("/{$this->path}/{$path}");
    }
}
