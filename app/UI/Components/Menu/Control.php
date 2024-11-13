<?php
declare(strict_types=1);
namespace App\UI\Components\Menu;
use Nette\Application\UI\Control as NetteControl;

use Nette\Security\User;
use Nette\SmartObject;

class Control extends NetteControl
{
  use SmartObject;

  public function __construct(
    private User $user
  ) {
  }

  public function render(): void
  {
    $this->template->setFile(__DIR__ . "/default.latte")->render();
  }
}