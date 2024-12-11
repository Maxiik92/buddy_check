<?php

declare(strict_types=1);

namespace App\Core\Security;

use App\Model\UserModel;
use App\UI\Trait\TranslatorTrait;
use Nette\Security\AuthenticationException;
use Nette\Security\Authenticator;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;

class UserAuthenticator implements Authenticator
{
  use TranslatorTrait;

  public function __construct(private UserModel $userModel, private Passwords $passwords)
  {
  }

  /**
   * @inheritDoc
   */
  public function authenticate(string $username, string $password): IIdentity
  {
    $user = null;
    $byUserName = $this->userModel->getByParameter('username', $username)->fetch();
    if (!$byUserName) {
      $byEmail = $this->userModel->getByParameter('email', $username)->fetch();
      if (!$byEmail) {
        throw new AuthenticationException($this->t('userNotFound'));
      } else {
        $user = $byEmail;
      }
    } else {
      $user = $byUserName;
    }
    $user = $user->related('user_x_role')->fetch();

    if (!$this->passwords->verify($password, $user->user->password)) {
      throw new AuthenticationException($this->t('incorrectPassword'));
    }

    return new SimpleIdentity(
      $user->user->id,
      $user->role->name,
      [
        'name' => implode(' ', [$user->user->first_name, $user->user->middle_name, $user->user->last_name]),
        'email' => $user->user->email,
      ]
    );
  }
}