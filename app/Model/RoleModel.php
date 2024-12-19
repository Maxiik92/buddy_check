<?php

namespace App\Model;

use App\Model\BaseModel;
use App\Model\Entity\Role;

class RoleModel extends BaseModel
{
  public function getTableName(): string
  {
    return 'role';
  }

  public function findAllByUserIdAsEntity(int $id): array
  {
    return array_map(
      function (string $name) use ($id) {
        return Role::create($id, $name);
      },
      $this->findByUserIdToSelect($id)
    );
  }

  public function findByUserIdToSelect(int $id): array
  {
    return $this->getTable()
      ->where(':user_x_role.user_id', $id)
      ->fetchPairs('id', 'name');
  }
}