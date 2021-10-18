<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// https://www.vaultproject.io/api-docs/auth
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

    $totp = new TOTP(client: $client);

    $createKey = $totp->createKey(
        key: 'my-key',
        issuer: 'MyApp',
        account: 'luca@castelnuovo.xyz'
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
        'createKey' => $createKey, // $createKey->barcode = <img src="data:image/png;base64,{$createKey->barcode}" />
        'listKeys' => $listKeys,
        'deleteKey' => $deleteKey
    ],

    'actions' => [
        'generate' => $generate,
        'verify' => $verify,
    ],
]);

/** Sample output
 * {
 * "key": {
 *  "createKey": {
 *   "barcode": "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",
 *  "url": "otpauth://totp/MyApp:luca@castelnuovo.xyz?algorithm=SHA256&digits=6&issuer=MyApp&period=30&secret=E7FGBLFJDPI6SZNX2YXDH2JKL2OITMUA26BWU3RIOEPLAYXZDZXA"
 *  },
 *  "listKeys": [
 *     "my-key",
 *     "my-key2"
 *   ],
 *    "deleteKey": null
 *   },
 *   "actions": {
 *     "generate": "18290536",
 *     "verify": true
 *   }
 * }
 */
