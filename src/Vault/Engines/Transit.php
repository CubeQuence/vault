<?php

declare(strict_types=1);

namespace CQ\Vault\Engines;

use CQ\Vault\Vault;

final class Transit
{
    public function __construct(
        private Vault $vault,
        private string $path = 'transit',
        private string $key = ''
    ) {
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    // TODO: createKey

    public function listKeys(): array
    {
        $response = $this->vault->list("/{$this->path}/keys");

        return $response->data->keys;
    }

    public function rotateKey(): void
    {
        $this->vault->post("/{$this->path}/keys/{$this->key}/rotate", ['json' => 'required']);
    }

    // TODO: deleteKey

    public function encrypt(string $plaintext): string
    {
        $response = $this->vault->post("/{$this->path}/encrypt/{$this->key}", [
            'plaintext' => base64_encode($plaintext),
        ]);

        return $response->data->ciphertext;
    }

    public function decrypt(string $ciphertext): string
    {
        $response = $this->vault->post("/{$this->path}/decrypt/{$this->key}", [
            'ciphertext' => $ciphertext,
        ]);

        return base64_decode($response->data->plaintext);
    }

    public function rewrap(string $ciphertext): string
    {
        $response = $this->vault->post("/{$this->path}/rewrap/{$this->key}", [
            'ciphertext' => $ciphertext,
        ]);

        return $response->data->ciphertext;
    }

    public function sign(string $plaintext): string
    {
        $response = $this->vault->post("/{$this->path}/hmac/{$this->key}", [
            'input' => base64_encode($plaintext),
        ]);

        return $response->data->hmac;
    }

    public function verify(string $plaintext, string $signature): bool
    {
        $response = $this->vault->post("/{$this->path}/verify/{$this->key}", [
            'input' => base64_encode($plaintext),
            'hmac' => $signature,
        ]);

        return $response->data->valid;
    }
}
