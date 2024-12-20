<?php

namespace App\Model;

use App\Model\BaseModel;
use App\Model\Entity\UserResource;
use Nette\Database\Table\ActiveRow;

class UserModel extends BaseModel
{
  public function getTableName(): string
  {
    return 'user';
  }

  public function toEntity(ActiveRow $row): UserResource
  {
    return UserResource::create($this->getTableName(), $row);
  }

  public function isUserNameTaken(string $username): bool
  {
    return $this->valueExistsInTable('username', $username);
  }

  public function isEmailTaken(string $email): bool
  {
    return $this->valueExistsInTable('email', $email);
  }
}