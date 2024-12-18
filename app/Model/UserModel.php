<?php

namespace App\Model;

use App\Model\BaseModel;

class UserModel extends BaseModel
{
  public function getTableName(): string
  {
    return 'user';
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