<?php
/* Copyright (C) 2019 ActiveNet s.r.o. - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by ActiveNet s.r.o. <info@activenet.sk>
 */
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