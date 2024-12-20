<?php
declare(strict_types=1);
namespace App\Core\Trait;

use App\UI\Trait\TranslatorTrait;

trait RequireLoggedUserTrait
{
  use TranslatorTrait;
  public function injectRequireLoggedUser()
  {
    $this->onStartup[] = function () {
      if (!$this->getUser()->isLoggedIn()) {
        $this->flashMessage($this->t('unauthorized'), 'danger');
        $this->redirect(':Front:Home:default');
      }
    };
  }
}