<?php

declare(strict_types=1);

namespace App\UI\User\Manipulate;

use App\Core\Factory\FormFactory;
use App\Core\Trait\RequireLoggedUserTrait;
use App\Model\UserModel;
use App\UI\Front\BasePresenter;
use App\UI\User\Trait\UserTrait;
use Nette\Application\UI\Form;
use Nette\Database\Table\ActiveRow;
use Nette\Security\Passwords;
use Nette\SmartObject;
use stdClass;

class ManipulatePresenter extends BasePresenter
{
  use SmartObject;
  use RequireLoggedUserTrait;
  use UserTrait;

  private string $resource = 'user';
  private string|int $id;

  private ActiveRow $userData;

  public function __construct(
    private FormFactory $formFactory,
    private UserModel $userModel,
    private Passwords $passwords,
  ) {
  }

  public function actionEdit(?int $id)
  {
    if ($id) {
      $resource = $this->userModel->getById($id);
      if (!$resource) {
        $this->flashMessage($this->t('userNotFound'), 'danger');
        $this->redirect(':Front:Home:default');
      }
      $this->checkPrivilege($this->userModel->toEntity($resource), 'edit');
      $this->userData = $resource;
      $this->id = $id;
    } else {
      $this->flashMessage($this->t('missingId'), 'danger');
      $this->redirect(':Front:Home:default');
    }
  }

  public function actionAdd()
  {
    if (!$this->getUser()->isAllowed($this->resource, 'create')) {
      $this->flashMessage($this->t('unauthorized'), 'danger');
      $this->redirect(':Front:Home:default');
    }
  }

  public function beforeRender()
  {
    $this->template->id = $this->id ?? null;
    $this->template->passwordRequirements = $this->t('passMinLength') . ': ' . self::MIN_PASS_LENGTH . '. ' . $this->t('passRequirements') . '.';
  }

  public function createComponentManipulateForm(): Form
  {
    $form = $this->formFactory->create();
    $form->getElementPrototype()
      ->setAttribute("class", "row g-3");

    $enter = ucfirst($this->t('enter'));
    $form->addText("username", "Username")
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', "{$enter} {$this->t('username')}");

    $form->addEmail('email', 'E-mail')
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', "{$enter} {$this->t('email')}");

    $form->addText("firstName", "First name")
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', "{$enter} {$this->t('firstname')}");

    $form->addText("middleName", "Middle name")
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', "{$enter} {$this->t('middlename')}");

    $form->addText("lastName", "Last name")
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', "{$enter} {$this->t('lastname')}");

    $form->addPassword('password', 'Password')
      ->addRule($form::MinLength, $this->t('passMinLength') . ': ' . self::MIN_PASS_LENGTH, self::MIN_PASS_LENGTH)
      ->addRule($form::Pattern, $this->t('passRequirements'), '^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$')
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', "{$enter} {$this->t('password')}");

    $confirm = ucfirst($this->t('confirm'));
    $form->addPassword('passwordConfirm', 'Confirm password')
      ->setRequired('Please confirm your password')
      ->addRule($form::EQUAL, 'Password mismatch', $form['password'])
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', "{$confirm} {$this->t('password')}");

    $form->addSubmit('register', $this->t('signup'))
      ->setHtmlAttribute('class', 'btn btn-primary');

    if (isset($this->userData)) {
      $form->setDefaults($this->userData);
    }
    $form->onSuccess[] = [$this, 'onSuccess'];
    return $form;
  }

  public function onSuccess(Form $form, stdClass $values)
  {
  }
}