<?php

declare(strict_types=1);

namespace App\UI\User\Register;

use App\Core\Factory\FormFactory;
use App\Model\UserModel;
use App\UI\Front\BasePresenter;
use Exception;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;
use Nette\SmartObject;
use stdClass;


final class RegisterPresenter extends BasePresenter
{
  use SmartObject;
  const MIN_PASS_LENGTH = 8;
  public function __construct(
    private FormFactory $formFactory,
    private UserModel $userModel,
    private Passwords $passwords
  ) {
  }

  public function renderDefault(): void
  {
    $this->template->passwordRequirements = $this->t('passMinLength') . ': ' . self::MIN_PASS_LENGTH . '. ' . $this->t('passRequirements') . '.';
  }

  public function createComponentForm(): Form
  {
    $form = $this->formFactory->create();

    $form->getElementPrototype()
      ->setAttribute("class", "row g-3 needs-validation ajax");

    $requiredMsg = 'Please enter %label';
    $enter = ucfirst($this->t('enter'));
    $form->addText("username", "Username")
      ->setRequired($requiredMsg)
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', "{$enter} {$this->t('username')}");

    $form->addEmail('email', 'E-mail')
      ->setRequired($requiredMsg)
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', "{$enter} {$this->t('email')}");

    $form->addText("firstName", "First name")
      ->setRequired($requiredMsg)
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', "{$enter} {$this->t('firstname')}");

    $form->addText("middleName", "Middle name")
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', "{$enter} {$this->t('middlename')}");

    $form->addText("lastName", "Last name")
      ->setRequired($requiredMsg)
      ->setHtmlAttribute('class', 'form-control')
      ->setHtmlAttribute('placeholder', "{$enter} {$this->t('lastname')}");

    $form->addPassword('password', 'Password')
      ->setRequired($requiredMsg)
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

    $form->onSuccess[] = [$this, 'onSuccess'];
    return $form;
  }

  public function onSuccess(Form $form, stdClass $data)
  {
    try {
      $uniqueChecker = $this->checkUniqueInputs($data);

      if (!empty($uniqueChecker)) {
        $this->flashMessage(implode('. ', $uniqueChecker), 'error');
        return;
      }

      $passwords = new Passwords(PASSWORD_BCRYPT, ['cost' => 12]);
      $resPass = $passwords->hash($data->password);

      $insert = [
        'username' => $data->username,
        'email' => $data->email,
        'first_name' => $data->firstName,
        'middle_name' => $data->middleName,
        'last_name' => $data->lastName,
        'password' => $resPass
      ];
      $this->userModel->insert($insert);
    } catch (Exception $e) {
      $form->addError($this->t('An error occurred during registration. Please try again later.'));
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

  public function handleCheckUnique(string $field, string $value)
  {
    $response = [];
    if ($field === 'username' && $this->userModel->isUserNameTaken($value)) {
      $response['error'] = $this->t('usernameTaken');
    } elseif ($field === 'email' && $this->userModel->isEmailTaken($value)) {
      $response['error'] = $this->t('emailTaken');
    }
    $this->sendJson($response);
  }
}
