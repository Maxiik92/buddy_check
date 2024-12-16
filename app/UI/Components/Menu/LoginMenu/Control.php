<?php
declare(strict_types=1);
namespace App\UI\Components\Menu\LoginMenu;

use App\Core\Factory\FormFactory;
use App\Core\Factory\UtilityFactory;
use Exception;
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
    $this->template->setFile(__DIR__ . "/default.latte")->render();
  }

  public function createComponentLoginForm(): Form
  {
    $form = $this->formFactory->create();
    $form->getElementPrototype()
      ->setAttribute("class", "ajax center-form");

    $form->addText("username", 'Username/Email')
      ->setRequired($this->presenter->t('usernameRequired'))
      ->setHtmlAttribute('autocomplete', 'username')
      ->setHtmlAttribute('class', 'form-control');

    $form->addPassword('password', 'Password')
      ->setRequired($this->presenter->t('passRequired'))
      ->setHtmlAttribute('autocomplete', 'current-password')
      ->setHtmlAttribute('class', 'form-control');

    $form->addSubmit('submit', $this->presenter->t('signIn'))
      ->setHtmlAttribute('class', 'btn btn-primary');

    $form->onSuccess[] = [$this, 'onSuccess'];
    return $form;
  }

  public function onSuccess(Form $form, stdClass $values)
  {
    try {
      $this->presenter->getUser()->login($values->username, $values->password);

      if ($this->presenter->getUser()->isLoggedIn()) {
        //LOG
        $this->flashMessage('loginSuccess', 'success');
        $this->redirect('this');
      } else {
        $this->flashMessage('loginFail', 'danger');
        $this->redirect('this');
      }
    } catch (Exception $e) {
      //LOG
      $this->flashMessage('loginFail', 'danger');
      $this->redirect('this');
    }
  }
}