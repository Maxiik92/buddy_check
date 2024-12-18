<?php

namespace App\Model;

use App\Model\BaseModel;

class AclActionModel extends BaseModel
{
  public function getTableName(): string
  {
    return 'acl_action';
  }
}