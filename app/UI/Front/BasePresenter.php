<?php

declare(strict_types=1);

namespace App\UI\Front;

use App\UI\Presenter;
use App\UI\Trait\ConfigTrait;
use App\UI\Trait\TranslatorTrait;
use Nette\SmartObject;
use App\UI\Components\Menu\PresenterTrait as MenuTrait;

abstract class BasePresenter extends Presenter
{
  use SmartObject;
  use TranslatorTrait;
  use MenuTrait;
  // use ConfigTrait;

}
