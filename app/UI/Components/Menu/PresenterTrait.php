<?php
declare(strict_types=1);
namespace App\UI\Components\Menu;

trait PresenterTrait
{
  private ControlFactory $menuControlFactory;

  public function injectMenuControlFactory(ControlFactory $controlfactory)
  {
    $this->menuControlFactory = $controlfactory;
  }

  public function createComponentMenu(): Control
  {
    if (!$this->menuControlFactory) {
      $this->error('Page not found', 404);
    }
    return $this->menuControlFactory->create($this->user);
  }
}