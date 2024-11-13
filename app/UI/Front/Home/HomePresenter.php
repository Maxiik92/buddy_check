<?php

declare(strict_types=1);

namespace App\UI\Front\Home;

use App\UI\Front\BasePresenter;
use Nette\SmartObject;
use App\UI\Components\Menu\PresenterTrait as MenuTrait;


final class HomePresenter extends BasePresenter
{
  use SmartObject;
  use MenuTrait;
  public function __construct()
  {
    $t = 'hejpic';
  }
}
