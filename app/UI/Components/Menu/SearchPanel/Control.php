<?php
declare(strict_types=1);
namespace App\UI\Components\Menu\SearchPanel;

use Nette\Application\UI\Control as NetteControl;

use Nette\SmartObject;

class Control extends NetteControl
{
  use SmartObject;

  public function __construct(
  ) {
  }

  public function render(): void
  {
    $this->template->setFile(__DIR__ . "/default.latte")->render();
  }
}