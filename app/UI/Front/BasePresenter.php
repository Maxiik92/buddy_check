<?php

declare(strict_types=1);

namespace App\UI\Front;

use App\UI\Presenter;
use Nette\SmartObject;
use App\UI\Components\Menu\PresenterTrait as MenuTrait;

abstract class BasePresenter extends Presenter
{
  use SmartObject;
  use MenuTrait;
}
