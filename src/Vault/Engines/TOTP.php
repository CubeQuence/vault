<?php

declare(strict_types=1);

namespace CQ\Vault\Engines;

use CQ\Vault\Client;

final class TOTP
{
    public function __construct(
        private Client $client,
        private string $path = 'totp'
    ) {
    }

    public function createKey(
        string $key,
        string $issuer,
        string $account,
        int $keySize = 32,
        int $period = 30,
        int $digits = 6, // can be 6 or 8
    ): object {
        $response = $this->client->post("/{$this->path}/keys/{$key}", [
            'generate' => true,
            'key_size' => $keySize,
            'issuer' => $issuer,
            'account_name' => $account,
            'period' => $period,
            'algorithm' => 'SHA256',
            'digits' => $digits,
        ]);

        return $response->data;
    }

    public function listKeys(): array
    {
        $response = $this->client->list("/{$this->path}/keys");

        return $response->data->keys;
    }

    public function deleteKey(string $key): void
    {
        $this->client->delete("/{$this->path}/keys/{$key}");
    }

    // Generate a TOTP token for a given key
    public function generate(string $key): string
    {
        $response = $this->client->get("/{$this->path}/code/{$key}");

        return $response->data->code;
    }

    // Validate a TOTP token for a given key
    public function verify(string $key, string $code): bool
    {
        $response = $this->client->post("/{$this->path}/code/{$key}", [
            'code' => $code,
        ]);

        return $response->data->valid;
    }
}
