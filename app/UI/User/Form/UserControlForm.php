<?php

namespace App\UI\User\Form;

use App\Core\Factory\FormFactory;
use App\UI\User\Trait\UserTrait;
use Nette\Application\UI\Form;
use Nette\Localization\Translator;
use Nette\SmartObject;

class UserControlFormFactory
{
  use SmartObject;
  use UserTrait;

  public function __construct(
    private Translator $translator,
    private FormFactory $formFactory,
  ) {
  }

  public function create(): Form
  {
    $form = $this->formFactory->create();
    $form->getElementPrototype()
      ->setAttribute("class", "row g-3");

    $enter = ucfirst($this->translator->translate('enter'));

    $form->addHidden('id');
    $form->addText("username", "Username")
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', "{$enter} {$this->translator->translate('username')}");

    $form->addEmail('email', 'E-mail')
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', "{$enter} {$this->translator->translate('email')}");

    $form->addText("first_name", "First name")
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', "{$enter} {$this->translator->translate('firstname')}");

    $form->addText("middle_name", "Middle name")
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', "{$enter} {$this->translator->translate('middlename')}");

    $form->addText("last_name", "Last name")
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', "{$enter} {$this->translator->translate('lastname')}");

    $form->addPassword('password', 'Password')
      ->addRule($form::MinLength, $this->translator->translate('passMinLength') . ': ' . self::MIN_PASS_LENGTH, self::MIN_PASS_LENGTH)
      ->addRule($form::Pattern, $this->translator->translate('passRequirements'), '^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$')
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', "{$enter} {$this->translator->translate('password')}");

    $confirm = ucfirst($this->translator->translate('confirm'));
    $form->addPassword('passwordConfirm', 'Confirm password')
      ->setRequired('Please confirm your password')
      ->addRule($form::EQUAL, 'Password mismatch', $form['password'])
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', "{$confirm} {$this->translator->translate('password')}")
      ->addConditionOn($form['password'], $form::FILLED)
      ->setRequired($this->translator->translate('confirmPassword'));

    $form->addSubmit('submit', $this->translator->translate('signup'))
      ->setHtmlAttribute('class', 'btn btn-primary');
    return $form;
  }
}