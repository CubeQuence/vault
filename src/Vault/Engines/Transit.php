<?php

declare(strict_types=1);

namespace CQ\Vault\Engines;

use CQ\Vault\Client;

final class Transit
{
    public function __construct(
        private Client $client,
        private string $path = 'transit'
    ) {
    }

    // TODO: createKey

    public function listKeys(): array
    {
        $response = $this->client->list("/{$this->path}/keys");

        return $response->data->keys;
    }

    public function rotateKey(string $key): void
    {
        $this->client->post("/{$this->path}/keys/{$key}/rotate", ['json' => 'required']);
    }

    // TODO: deleteKey

    public function encrypt(string $key, string $plaintext): string
    {
        $response = $this->client->post("/{$this->path}/encrypt/{$key}", [
            'plaintext' => base64_encode($plaintext),
        ]);

        return $response->data->ciphertext;
    }

    public function decrypt(string $key, string $ciphertext): string
    {
        $response = $this->client->post("/{$this->path}/decrypt/{$key}", [
            'ciphertext' => $ciphertext,
        ]);

        return base64_decode($response->data->plaintext);
    }

    public function rewrap(string $key, string $ciphertext): string
    {
        $response = $this->client->post("/{$this->path}/rewrap/{$key}", [
            'ciphertext' => $ciphertext,
        ]);

        return $response->data->ciphertext;
    }

    public function sign(string $key, string $plaintext): string
    {
        $response = $this->client->post("/{$this->path}/hmac/{$key}", [
            'input' => base64_encode($plaintext),
        ]);

        return $response->data->hmac;
    }

    public function verify(string $key, string $plaintext, string $signature): bool
    {
        $response = $this->client->post("/{$this->path}/verify/{$key}", [
            'input' => base64_encode($plaintext),
            'hmac' => $signature,
        ]);

        return $response->data->valid;
    }
}
