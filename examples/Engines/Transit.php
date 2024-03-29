<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// https://www.vaultproject.io/api/secret/transit

use CQ\Vault\Engines\Transit;
use CQ\Vault\Vault;

try {
    $vault = new Vault(
        token: 's.1mfDZPS7t95g1ZubOdMuYcOJ',
        baseUri: 'http://127.0.0.1:8200',
    );

    $transit = new Transit(
        vault: $vault,
        path: 'transit', // optional
        // key: 'key1-aes256' // this can be set if you already know the key you want to use
    );

    $string = 'Hello World';

    $listKeys = $transit->listKeys();
    $transit->setKey(key: $listKeys[0]);

    $encrypt = $transit->encrypt(plaintext: $string);
    $decrypt = $transit->decrypt(ciphertext: $encrypt);

    $sign = $transit->sign(plaintext: $string);
    $verify = $transit->verify(plaintext: $string, signature: $sign);

    $rotateKey = $transit->rotateKey();

    $rewrap = $transit->rewrap(ciphertext: $encrypt);
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
