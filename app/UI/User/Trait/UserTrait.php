<?php
declare(strict_types=1);
namespace App\UI\User\Trait;

trait UserTrait
{
  const MIN_PASS_LENGTH = 8;
  public function handleCheckUnique(string $field, string $value)
  {
    $response = [];
    $taken = null;
    $userIdentity = $this->getUser()->getIdentity();

    $isTaken = function ($value, $field) use ($userIdentity) {
      //logged in users ignore own value
      if ($userIdentity) {
        if ($field === 'username' && $value != $userIdentity->username && $this->userModel->isUserNameTaken($value)) {
          return true;
        } elseif ($field === 'email' && $value != $userIdentity->email && $this->userModel->isEmailTaken($value)) {
          return true;
        }
      } else {
        if ($field === 'username' && $this->userModel->isUserNameTaken($value)) {
          return true;
        } elseif ($field === 'email' && $this->userModel->isEmailTaken($value)) {
          return true;
        }
      }
      return false;
    };

    if ($field === 'username' && $isTaken($value, 'username')) {
      $taken = 'usernameTaken';
    } elseif ($field === 'email' && $isTaken($value, 'email')) {
      $taken = 'emailTaken';
    }

    if ($taken) {
      $response['error'] = $this->t($taken);
    }
    $this->sendJson($response);
  }
}