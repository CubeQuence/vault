<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// https://www.vaultproject.io/api-docs/secret/totp

use CQ\Vault\Auth\Provider\Token;
use CQ\Vault\Client;
use CQ\Vault\Engines\TOTP;

try {
    $tokenProvider = new Token(token: 's.1mfDZPS7t95g1ZubOdMuYcOJ');

    $client = new Client(
        authProvider: $tokenProvider,
        baseUri: 'http://127.0.0.1:8200',
    );

    $totp = new TOTP(
        client: $client,
        path: 'totp' // optional
    );

    $createKey = $totp->createKey(
        key: 'my-key',
        issuer: 'MyApp',
        account: 'foobar@example.com'
    );

    $listKeys = $totp->listKeys();
    $deleteKey = $totp->deleteKey(key: 'my-key2');

    $generate = $totp->generate(key: $listKeys[0]);
    $verify = $totp->verify(key: $listKeys[0], code: $generate);
} catch (\Throwable $th) {
    echo $th->getMessage();
    exit;
}

echo json_encode([
    'key' => [
        'createKey' => $createKey, // $createKey->barcode --> <img src="data:image/png;base64,{$createKey->barcode}" />
        'listKeys' => $listKeys,
        'deleteKey' => $deleteKey
    ],

    'actions' => [
        'generate' => $generate,
        'verify' => $verify,
    ],
]);

/** Sample output
 *{
 *  "key": {
 *    "createKey": {
 *      "barcode": "iVBORw0KGgoAAAANSUhEUgAAAMgAA...",
 *      "url": "otpauth://totp/MyApp:..."
 *    },
 *    "listKeys": [
 *      "my-key",
 *      "my-key2"
 *    ],
 *    "deleteKey": null
 *  },
 *
 *  "actions": {
 *    "generate": "182905",
 *    "verify": true
 *  }
 *}
 */
