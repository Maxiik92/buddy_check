<?php

namespace App\Model;

use App\Model\BaseModel;

class AclResourceModel extends BaseModel
{
  public function getTableName(): string
  {
    return 'acl_resource';
  }
}