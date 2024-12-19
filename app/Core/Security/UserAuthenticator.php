<?php

declare(strict_types=1);

namespace App\Core\Security;

use App\Model\RoleModel;
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

  public function __construct(private UserModel $userModel, private RoleModel $roleModel, private Passwords $passwords)
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

    if ($user->deleted == 1) {
      throw new AuthenticationException($this->t('userDeleted'));
    }

    if (!$this->passwords->verify($password, $user->password)) {
      throw new AuthenticationException($this->t('incorrectPassword'));
    }

    $this->userModel->updateByParam('id', $user->id, ['logged' => 1]);

    $userData = $user->toArray();
    $userData['logged'] = 1;
    unset($userData['password']);

    return new SimpleIdentity(
      $user->id,
      $this->roleModel->findAllByUserIdAsEntity($user->id),
      $userData
    );
  }
}