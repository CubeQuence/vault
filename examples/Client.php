<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// https://www.vaultproject.io/api-docs/auth
// https://www.vaultproject.io/api-docs/secret

use CQ\Vault\Auth\Provider\AppRole;
use CQ\Vault\Auth\Provider\Token;
use CQ\Vault\Client;

try {
    $tokenProvider = new Token(token: 's.1mfDZPS7t95g1ZubOdMuYcOJ');
    // $approleProvider = new AppRole(
    //     roleId: 'XXXXXX-XXXXXX-XXXXXX-XXXXXX',
    //     secretId: 'XXXXXX-XXXXXX-XXXXXX-XXXXXX'
    // );

    // Login client using provider
    $client = new Client(
        authProvider: $tokenProvider,
        baseUri: 'http://127.0.0.1:8200',
        version: 'v1' // Optional variable
    );

    // Example operations
    $write = $client->post(
        path: '/kv1/helloworld',
        body: [
            'foo' => 'bar',
        ]
    );
    $read = $client->get(path: '/kv1/helloworld');
    $keys = $client->list(path: '/kv1');
    $revoke = $client->delete(path: '/kv1/helloworld');
} catch (\Throwable $th) {
    echo $th->getMessage();
    exit;
}

echo json_encode([
    'write' => $write, // NoContent
    'read' => $read,
    'keys' => $keys,
    'revoke' => $revoke, // NoContent
]);
