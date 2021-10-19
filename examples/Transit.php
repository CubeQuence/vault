<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// https://www.vaultproject.io/api/secret/transit

use CQ\Vault\Auth\Provider\Token;
use CQ\Vault\Client;
use CQ\Vault\Engines\Transit;

try {
    $tokenProvider = new Token(token: 's.1mfDZPS7t95g1ZubOdMuYcOJ');

    $client = new Client(
        authProvider: $tokenProvider,
        baseUri: 'http://127.0.0.1:8200',
    );

    $transit = new Transit(
        client: $client,
        path: 'transit' // optional
    );

    $string = 'Hello World';

    $listKeys = $transit->listKeys();
    $key = $listKeys[0];

    $encrypt = $transit->encrypt(key: $key, plaintext: $string);
    $decrypt = $transit->decrypt(key: $key, ciphertext: $encrypt);

    $sign = $transit->sign(key: $key, plaintext: $string);
    $verify = $transit->verify(
        key: $key,
        plaintext: $string,
        signature: $sign
    );

    $rotateKey = $transit->rotateKey(key: $key);

    $rewrap = $transit->rewrap(key: $key, ciphertext: $encrypt);
} catch (\Throwable $th) {
    echo $th->getMessage();
    exit;
}

echo json_encode([
    'string' => $string,

    'key' => [
        'listKeys' => $listKeys,
        'rotateKey' => $rotateKey
    ],

    'actions' => [
        'decrypt' => $decrypt,
        'encrypt' => $encrypt,
        'sign' => $sign,
        'verify' => $verify,
        'rewrap' => $rewrap,
    ],
]);

/** Sample Output
 *{
 *  "string": "Hello World",
 *
 *  "key": {
 *    "listKeys": [
 *      "key1-aes256",
 *      "key2-rsa2048"
 *    ],
 *    "rotateKey": null,
 *  },
 *
 *  "actions": {
 *    "decrypt": "Hello World",
 *    "encrypt": "vault:v1:...",
 *    "sign": "vault:v1:...",
 *    "verify": true,
 *    "rewrap": "vault:v2:..."
 *  }
 *}
 */
