<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Nette\Security\IRole;

class Role implements IRole
{
    public function __construct(
        private int $userId,
        private string $roleId,
    ) {
    }

    function getRoleId(): string
    {
        return $this->roleId;
    }

    function getUserId(): int
    {
        return $this->userId;
    }

    public static function create(int $userId, string $roleId): self
    {
        return new Role($userId, $roleId);
    }
}