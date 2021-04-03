<?php

declare(strict_types=1);

namespace Vault\Models;

final class Token
{
    public function __construct(
        private string $clientToken,
        private string | null $accessor = null,
        private int | null $creationTime = null,
        private int | null $creationTtl = null,
        private string | null $displayName = null,
        private int | null $errorxplicitMaxTtl = null,
        private string | null $id = null,
        private array $meta = [],
        private int $numUses = 0,
        private bool $orphan = false,
        private string | null $path = null,
        private array $policies = [],
        private int | null $ttl = null,
        private string | null $requestId = null,
        private string | null $leaseId = null,
        private int | null $leaseDuration = null,
        private bool | null $renewable = null,
        private object | null $data = null,
        private string | null $wrapInfo = null,
        private string | null $warnings = null,
        private string | null $auth = null,
    ) {
        // TODO: var_dump all variables
    }

    public function getClientToken(): string | null
    {
        return $this->clientToken;
    }

    public function getAccessor(): string | null
    {
        return $this->accessor;
    }

    public function getCreationTime(): int | null
    {
        return $this->creationTime;
    }

    public function getCreationTtl(): int | null
    {
        return $this->creationTtl;
    }

    public function getDisplayName(): string | null
    {
        return $this->displayName;
    }

    public function getExplicitMaxTtl(): int | null
    {
        return $this->explicitMaxTtl;
    }

    public function getId(): string | null
    {
        return $this->id;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function getNumUses(): int
    {
        return $this->numUses;
    }

    public function isOrphan(): bool
    {
        return $this->orphan;
    }

    public function getPath(): string | null
    {
        return $this->path;
    }

    public function getPolicies(): array
    {
        return $this->policies;
    }

    public function getTtl(): int | null
    {
        return $this->ttl;
    }

    // ********** //

    public function isTokenExpired(): bool
    {
        if ($this->creationTtl <= 0) {
            return false;
        }

        return time() > $this->creationTime + $this->creationTtl;
    }
}
