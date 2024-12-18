<?php

namespace App\Model;

use App\Model\BaseModel;

class RoleModel extends BaseModel
{
  public function getTableName(): string
  {
    return 'role';
  }
}