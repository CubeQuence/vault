<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use CQ\Vault\Vault;

try {
    $vault = new Vault(
        token: 's.1mfDZPS7t95g1ZubOdMuYcOJ',
        baseUri: 'http://127.0.0.1:8200',
        version: 'v1' // Optional variable
    );

    // Example operations
    $post = $vault->post(
        path: '/kv1/helloworld',
        body: [
            'foo' => 'bar',
        ]
    );
    $put = $vault->put(
        path: '/kv1/helloworld',
        body: [
            'foo' => 'bar2',
        ]
    );
    $get = $vault->get(path: '/kv1/helloworld');
    $list = $vault->list(path: '/kv1');
    $delete = $vault->delete(path: '/kv1/helloworld');
} catch (\Throwable $th) {
    echo $th->getMessage();
    exit;
}

echo json_encode([
    'post' => $post, // NoContent
    'put' => $put, // NoContent
    'get' => $get,
    'list' => $list,
    'delete' => $delete, // NoContent
]);
