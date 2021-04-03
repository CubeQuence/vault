<?php

declare(strict_types=1);

namespace Vault\Models;

use Vault\Exceptions\TokenException;

final class Token
{
    public function __construct(
        private string $token,
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
        if (! $this->creationTime || ! $this->creationTtl) {
            throw new TokenException('Token creationTime or creationTtl not defined');
        }

        if ($this->creationTtl <= 0) {
            return false;
        }

        return time() > $this->creationTime + $this->creationTtl;
    }
}
