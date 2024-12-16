<?php
declare(strict_types=1);
namespace App\UI\Components\Menu\LoginMenu;

use Nette\Security\User;

interface ControlFactory
{
  public function create(
    User $user,
  ): Control;
}