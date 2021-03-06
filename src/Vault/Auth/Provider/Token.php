<?php

namespace Vault\Auth\Provider;

class Token
{
    public function __construct(
        private string $token
    ) {
        //
    }
}
