<?php
declare(strict_types=1);
namespace App\UI\Components\Menu\LoginMenu\LoggedInMenu;

use App\Core\Factory\FormFactory;
use App\Core\Factory\UtilityFactory;
use App\Model\UserModel;
use Nette\Application\UI\Control as NetteControl;
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
    private SearchPanelControlFactory $searchPanelControlFactory,
    private UserModel $userModel,
  ) {
  }

  public function render(): void
  {
    $this->template->setFile(__DIR__ . "/default.latte")->render();
  }

  public function createComponentLoggedInSearchPanel()
  {
    return $this->searchPanelControlFactory->create();
  }

  public function handleLogout()
  {
    $this->userModel->updateByParam('id', $this->user->getId(), ['logged' => 0]);
    $this->user->logout();
    $this->presenter->redirect(':Front:Home:default');
  }

}