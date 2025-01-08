<?php

declare(strict_types=1);

namespace App\UI\User\Register;

use App\UI\User\Form\UserControlFormFactory;
use App\Model\UserModel;
use App\Model\UserRoleModel;
use App\UI\Front\BasePresenter;
use App\UI\User\Trait\UserTrait;
use Exception;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;
use Nette\SmartObject;
use stdClass;


final class RegisterPresenter extends BasePresenter
{
  use SmartObject;
  use UserTrait;
  public function __construct(
    private UserControlFormFactory $formFactory,
    private UserModel $userModel,
    private UserRoleModel $userRoleModel,
    private Passwords $passwords
  ) {
  }

  public function actionDefault()
  {
    if ($this->getUser()->isLoggedIn()) {
      $this->redirect(":Front:Home:default");
    }
  }

  public function renderDefault(): void
  {
    $this->template->passwordRequirements = $this->t('passMinLength') . ': ' . self::MIN_PASS_LENGTH . '. ' . $this->t('passRequirements') . '.';
  }

  public function createComponentForm(): Form
  {
    $form = $this->formFactory->create();

    $form->onSuccess[] = [$this, 'onSuccess'];
    return $form;
  }

  public function onSuccess(Form $form, stdClass $data)
  {
    $uniqueChecker = $this->checkUniqueInputs($data);

    if (!empty($uniqueChecker)) {
      $this->flashMessage(implode('. ', $uniqueChecker), 'error');
      return;
    }

    $passwords = new Passwords(PASSWORD_BCRYPT, ['cost' => 12]);
    $resPass = $passwords->hash($data->password);

    $this->userModel->beginTransaction();
    try {
      $insert = [
        'username' => $data->username,
        'email' => $data->email,
        'first_name' => $data->first_name,
        'middle_name' => $data->middle_name,
        'last_name' => $data->last_name,
        'password' => $resPass
      ];
      $user = $this->userModel->insert($insert);

      $this->userRoleModel->insert([
        'user_id' => $user->id,
        'role_id' => $this->userRoleModel->getDefaultRoleId(),
      ]);
      $this->userModel->commit();
    } catch (Exception $e) {
      $form->addError($this->t('An error occurred during registration. Please try again later.'));
      $this->userModel->rollBack();
      // $this->logger->error('Registration error: ' . $e->getMessage());
      $this->redirect($this);
    }
    $this->flashMessage($this->t('Registration successful! Please sign in.'), 'success');
    $this->redirect(':Front:Home:default');
  }

  public function checkUniqueInputs(stdClass $data)
  {
    $output = [];
    if ($this->userModel->isUserNameTaken($data->username)) {
      $output[] = ($this->t('usernameTaken'));
    }
    if ($this->userModel->isEmailTaken($data->email)) {
      $output[] = $this->t('emailTaken');
    }
    return $output;
  }
}
