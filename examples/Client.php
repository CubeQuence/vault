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

    $client = new Client(
        authProvider: $tokenProvider,
        baseUri: 'http://127.0.0.1:8200',
        version: 'v1' // Optional variable
    );

    $write = $client->post('/kv1/helloworld', [
        'foo' => 'bar',
    ]);
    $read = $client->get('/kv1/helloworld');
    $keys = $client->list('/kv1');
    $revoke = $client->delete('/kv1/helloworld');
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
