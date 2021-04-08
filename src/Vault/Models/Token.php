<?php

declare(strict_types=1);

namespace CQ\Vault\Models;

final class Token
{
    public function __construct(
        private string | null $token = null,
        private int | null $creationTime = null,
        private int | null $creationTtl = null
    ) {
    }

    public function getToken(): string | null
    {
        return $this->token ?: null;
    }

    public function isExpired(): bool
    {
        if ($this->creationTtl <= 0) {
            return false;
        }

        return time() > $this->creationTime + $this->creationTtl;
    }
}
