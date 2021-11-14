<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// https://www.vaultproject.io/api-docs/secret/kv/kv-v1

use CQ\Vault\Engines\KV1;
use CQ\Vault\Vault;

try {
    $vault = new Vault(
        token: 's.1mfDZPS7t95g1ZubOdMuYcOJ',
        baseUri: 'http://127.0.0.1:8200',
    );

    $kv1 = new KV1(
        vault: $vault,
        path: 'kv1' // Optional
    );

    $list = $kv1->list();
    $create = $kv1->create(
        path: 'helloworld',
        data: [
            'foo' => 'bar2'
        ]
    );
    $read = $kv1->read(path: 'helloworld');
    $update = $kv1->update(
        path: 'helloworld',
        data: [
            'foo2' => 'bar2'
        ]
    );
    $delete = $kv1->delete(path: 'helloworld');
} catch (\Throwable $th) {
    echo $th->getMessage();
    exit;
}

echo json_encode([
    'read' => $read,
    'list' => $list,
    'create' => $create,
    'update' => $update,
    'delete' => $delete,
]);

/** Sample Output
 *{
 *  "read": {
 *    "foo": "bar2"
 *  },
 *  "list": [
 *    "helloworld"
 *  ],
 *  "create": null,
 *  "update": null,
 *  "delete": null
 *}
 */
