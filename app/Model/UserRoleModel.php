<?php

namespace App\Model;

use App\Model\BaseModel;

class UserRoleModel extends BaseModel
{
  private int $defaultRoleId = 2;

  public function getTableName(): string
  {
    return 'user_x_role';
  }

  public function getDefaultRoleId()
  {
    return $this->defaultRoleId;
  }
}