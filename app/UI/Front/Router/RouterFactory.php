<?php

declare(strict_types=1);

namespace App\UI\FrontModule\Router;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
  use Nette\StaticClass;

  public static function createRouter(): RouteList
  {
    $router = new RouteList;
    $router
      ->withModule('Front') //prefix modulu aby sa nemusel pisat do kazdej cesty
      ->addRoute('/', 'Home:default');
    return $router;
  }
}