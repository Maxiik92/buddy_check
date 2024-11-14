<?php

declare(strict_types=1);

namespace App\UI\UserModule\Router;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
  use Nette\StaticClass;

  public static function createRouter(): RouteList
  {
    $router = new RouteList;
    $router
      ->withModule('User') //prefix modulu aby sa nemusel pisat do kazdej cesty
      ->withPath('user')
      ->addRoute('register', 'Register:default')
      ->addRoute('<presenter>/<action>', 'Home:default');

    return $router;
  }
}