<?php

declare(strict_types=1);

namespace App\UI\Front;

use App\UI\Presenter;
use Nette\SmartObject;


abstract class BasePresenter extends Presenter
{
  use SmartObject;
}
