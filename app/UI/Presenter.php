<?php

declare(strict_types=1);

namespace App\UI;

use App\Model\Entity\Resource;
use Nette\Application\UI\Presenter as NetteUiPresenter;

abstract class Presenter extends NetteUiPresenter
{
  protected function checkPrivilege(string|Resource $resource, string $privilege): void
  {
    if (!$this->getUser()->isAllowed($resource, $privilege)) {
      $this->flashMessage($this->presenter->t('unauthorized'), 'danger');
      $this->redirect(':Front:Home:default');
    }
  }
}
