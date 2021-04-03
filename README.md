<p align="center"><a href="https://github.com/CubeQuence/vault"><img src="https://rawcdn.githack.com/CubeQuence/CubeQuence/855a8fe836989ca40c4e50a889362975eab9ac43/public/assets/images/banner.png"></a></p>

<p align="center">
<a href="https://packagist.org/packages/cubequence/vault"><img src="https://poser.pugx.org/cubequence/vault/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/cubequence/vault"><img src="https://poser.pugx.org/cubequence/vault/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/cubequence/vault"><img src="https://poser.pugx.org/cubequence/vault/license.svg" alt="License"></a>
</p>

# Vault

PHP8 HashiCorp Vault client

## Installation

1. `composer require cubequence/vault`

## Demo Code
```php
<?php

require './vendor/autoload.php';

// https://www.vaultproject.io/api-docs/auth
// https://www.vaultproject.io/api-docs/secret

use Vault\Auth\Provider\Token;
use Vault\Client;

try {
    $client = new Client(
        authProvider: new Token(token: 's.XXXXXXXXXXXX'), // Other authProviders are available
        baseUri: 'http://127.0.0.1:8200',
        version: 'v1' // Optional variable
    );

    $write = $client->write('/kv1/helloworld', [
        'foo' => 'bar',
    ]);
    $read = $client->read('/kv1/helloworld');
    $keys = $client->keys('/kv1');
    $revoke = $client->revoke('/kv1/helloworld');
} catch (\Throwable $th) {
    echo $th->getMessage();
    exit;
}

echo json_encode([
    "write" => $write, // NoContent
    "read" => $read,
    "keys" => $keys,
    "revoke" => $revoke, // NoContent
]);
```

## Security Vulnerabilities

Please review [our security policy](https://github.com/CubeQuence/vault/security/policy) on how to report security vulnerabilities.

## License

Heavily inspired by:
- https://github.com/mittwald/vaultPHP
- https://github.com/CSharpRU/vault-php

Copyright © 2020 [Luca Castelnuovo](https://github.com/Luca-Castelnuovo). <br />
This project is [MIT](LICENSE.md) licensed.
