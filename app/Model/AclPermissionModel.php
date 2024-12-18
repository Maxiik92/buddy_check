<?php

namespace App\Model;

use App\Model\BaseModel;

class AclPermissionModel extends BaseModel
{
  public function getTableName(): string
  {
    return 'acl_permission';
  }
}