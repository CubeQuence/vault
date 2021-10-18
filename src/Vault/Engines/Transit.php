<?php

declare(strict_types=1);

namespace CQ\Vault\Engines;

use CQ\Vault\Client;

final class Transit
{
    public function __construct(private Client $client)
    {
    }

    public function encrypt(string $key, string $plaintext): string
    {
        $response = $this->client->post("/transit/encrypt/{$key}", [
            'plaintext' => base64_encode($plaintext),
        ]);

        return $response->data->ciphertext;
    }

    public function decrypt(string $key, string $ciphertext): string
    {
        $response = $this->client->post("/transit/decrypt/{$key}", [
            'ciphertext' => $ciphertext,
        ]);

        return base64_decode($response->data->plaintext);
    }

    public function sign(string $key, string $plaintext): string
    {
        $response = $this->client->post("/transit/hmac/{$key}", [
            'input' => base64_encode($plaintext),
        ]);

        return $response->data->hmac;
    }

    public function verify(string $key, string $plaintext, string $signature): bool
    {
        $response = $this->client->post("/transit/verify/{$key}", [
            'input' => base64_encode($plaintext),
            'hmac' => $signature,
        ]);

        return $response->data->valid;
    }

    public function rewrap(string $key, string $ciphertext): string
    {
        $response = $this->client->post("/transit/rewrap/{$key}", [
            'ciphertext' => $ciphertext,
        ]);

        return $response->data->ciphertext;
    }

    public function listKeys(): array
    {
        $response = $this->client->list('/transit/keys');

        return $response->data->keys;
    }

    public function rotateKey(string $key): void
    {
        $this->client->post("/transit/keys/{$key}/rotate", ['json' => 'required']);
    }
}
