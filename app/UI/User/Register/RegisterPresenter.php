<?php

declare(strict_types=1);

namespace App\UI\User\Register;

use App\Core\FormFactory;
use App\UI\Front\BasePresenter;
use Nette\Application\UI\Form;
use Nette\SmartObject;
use stdClass;


final class RegisterPresenter extends BasePresenter
{
  use SmartObject;
  public function __construct(
    private FormFactory $formFactory,
  ) {
  }

  public function renderDefault(): void
  {
  }

  public function createComponentForm(): Form
  {
    $form = $this->formFactory->create();

    $form->getElementPrototype()
      ->setAttribute("class", "row g-3 needs-validation ajax");

    $requiredMsg = 'Please enter %label';
    $form->addText("username", "Username")
      ->setRequired($requiredMsg)
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', 'Enter %label');

    $form->addEmail('email', 'E-mail')
      ->setRequired($requiredMsg)
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', 'Enter %label');

    $form->addText("firstName", "First name")
      ->setRequired($requiredMsg)
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', 'Enter %label');

    $form->addText("middleName", "Middle name")
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', 'Enter %label');

    $form->addText("lastName", "Last name")
      ->setRequired($requiredMsg)
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', 'Enter %label');

    $form->addPassword('password', 'Password')
      ->setRequired($requiredMsg)
      ->addRule($form::MinLength, 'Your password has to be at least %d long', 8)
      ->addRule($form::Pattern, 'Must contain number', '.*[0-9].*')
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', 'Enter %label');

    $form->addPassword('passwordConfirm', 'Confirm password')
      ->setRequired('Please confirm your password')
      ->addRule($form::EQUAL, 'Password mismatch', $form['password'])
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', 'Confirm %label');

    $form->addSubmit('register', 'Sign in')
      ->setHtmlAttribute('class', 'btn btn-primary');

    $form->onSuccess[] = [$this, 'onSuccess'];
    return $form;
  }

  public function onSuccess(Form $form, stdClass $data)
  {
    $d = $data;
  }
}
