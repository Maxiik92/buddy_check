<?php

declare(strict_types=1);

namespace App\UI\User\Manipulate;

use App\UI\User\Form\UserControlFormFactory;
use App\Core\Trait\RequireLoggedUserTrait;
use App\Model\UserModel;
use App\UI\Front\BasePresenter;
use App\UI\User\Trait\UserTrait;
use Exception;
use Nette\Application\UI\Form;
use Nette\Database\Table\ActiveRow;
use Nette\Security\Passwords;
use Nette\SmartObject;
use Nette\Utils\DateTime;
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
    private UserControlFormFactory $formFactory,
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
      $this->template->formUserId = $id;
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

    if (isset($this->userData)) {
      $this->template->manipulateForm = true;
      $form->setDefaults($this->userData);
    }
    if ($this->action === 'edit') {
      $form->getComponent('password')->setRequired(false);
      $form->getComponent('passwordConfirm')->setRequired(false);
    }
    $form->onSuccess[] = [$this, 'onSuccess'];
    return $form;
  }

  public function onSuccess(Form $form, stdClass $data)
  {
    if (isset($data->id) && $data->id != '') {
      $oldUser = $this->userModel->getById($data->id);
      if ($oldUser) {
        $this->editUser($form, $data, $oldUser);
      } else {
        $form->addError($this->t('userNotFound'));
        $this->flashMessage($this->t('userNotFound'), 'danger');
        $this->redirect('this');
      }
    } else {
      $this->createUser($form, $data);
    }

  }

  private function editUser(Form $form, stdClass $data, ActiveRow $oldData)
  {
    try {
      $update = [];
      unset($data->id);
      unset($data->passwordConfirm);

      foreach ($data as $item => $value) {
        if ($item !== 'password') {
          if ($value !== $oldData->{$item}) {
            $update[$item] = $value;
          }
        } else {
          if (isset($value) && $value !== '') {
            $passwords = new Passwords(PASSWORD_BCRYPT, ['cost' => 12]);
            $update['password'] = $passwords->hash($data->password);
          }
        }
      }
      if (!empty($update)) {
        $update['updated'] = new DateTime();
        $this->userModel->updateByParam('id', $oldData->id, $update);
      }
      $this->flashMessage($this->t('updateSuccessfull'), 'success');
    } catch (Exception $e) {
      $this->flashMessage($this->t('failedToEditUser'), 'danger');
    }
  }

  private function createUser(Form $form, stdClass $data)
  {
  }
}