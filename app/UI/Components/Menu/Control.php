<?php
declare(strict_types=1);
namespace App\UI\Components\Menu;
use App\Core\Factory\FormFactory;
use App\Core\Factory\UtilityFactory;
use Nette\Application\UI\Control as NetteControl;
use App\UI\Components\Menu\LoginMenu\ControlFactory as LoginMenuControlFactory;
use App\UI\Components\Menu\SearchPanel\ControlFactory as SearchPanelControlFactory;

use Nette\Security\User;
use Nette\SmartObject;

class Control extends NetteControl
{
  use SmartObject;

  public function __construct(
    private User $user,
    private UtilityFactory $utilityFactory,
    private FormFactory $formFactory,
    private LoginMenuControlFactory $loginMenuControlFactory,
    private SearchPanelControlFactory $searchPanelControlFactory,
  ) {
  }

  public function render(): void
  {
    $this->template->cssFilePath = $this->utilityFactory->getFilePathIfExists("/css/Components/Menu/menu.css");
    $this->template->setFile(__DIR__ . "/default.latte")->render();
  }

  public function createComponentLoginMenu()
  {
    return $this->loginMenuControlFactory->create($this->user);
  }

  public function createComponentSearchPanel()
  {
    return $this->searchPanelControlFactory->create();
  }
}