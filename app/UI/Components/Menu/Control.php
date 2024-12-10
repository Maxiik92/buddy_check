<?php
declare(strict_types=1);
namespace App\UI\Components\Menu;
use App\Core\Factory\FormFactory;
use App\Core\Factory\UtilityFactory;
use Nette\Application\UI\Control as NetteControl;

use Nette\Application\UI\Form;
use Nette\Security\User;
use Nette\SmartObject;
use stdClass;

class Control extends NetteControl
{
  use SmartObject;

  public function __construct(
    private User $user,
    private UtilityFactory $utilityFactory,
    private FormFactory $formFactory,
  ) {
  }

  public function render(): void
  {
    $this->template->cssFilePath = $this->utilityFactory->getFilePathIfExists("/css/Components/Menu/menu.css");
    $this->template->setFile(__DIR__ . "/default.latte")->render();
  }

  public function createComponentLoginForm(): Form
  {
    $form = $this->formFactory->create();
    $form->getElementPrototype()
      ->setAttribute("class", "row ajax");

    $form->addText("username", 'Username')
      ->setRequired($this->presenter->t('usernameRequired'))
      ->setHtmlAttribute('class', 'form-control');

    $form->addPassword('password', 'Password')
      ->setRequired($this->presenter->t('passRequired'))
      ->setHtmlAttribute('class', 'form-control');

    $form->addSubmit('submit', $this->presenter->t('signIn'))
      ->setHtmlAttribute('class', 'btn btn-primary');

    $form->onSuccess[] = [$this, 'onSuccess'];
    return $form;
  }

  public function onSuccess(Form $form, stdClass $values)
  {

  }
}