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
      ->addRoute('edit[/<id>]', 'Manipulate:edit')
      ->addRoute('create', 'Manipulate:add')
      ->addRoute('<presenter>/<action>[/<id>]', '<presenter>:<action>'); // General dynamic route
    return $router;
  }
}