<?php

declare(strict_types=1);

namespace CQ\Vault\Engines;

use CQ\Vault\Vault;

final class TOTP
{
    public function __construct(
        private Vault $vault,
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
        $response = $this->vault->post("/{$this->path}/keys/{$key}", [
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
        $response = $this->vault->list("/{$this->path}/keys");

        return $response->data->keys;
    }

    public function deleteKey(string $key): void
    {
        $this->vault->delete("/{$this->path}/keys/{$key}");
    }

    // Generate a TOTP token for a given key
    public function generate(string $key): string
    {
        $response = $this->vault->get("/{$this->path}/code/{$key}");

        return $response->data->code;
    }

    // Validate a TOTP token for a given key
    public function verify(string $key, string $code): bool
    {
        $response = $this->vault->post("/{$this->path}/code/{$key}", [
            'code' => $code,
        ]);

        return $response->data->valid;
    }
}
