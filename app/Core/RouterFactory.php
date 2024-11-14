<?php

declare(strict_types=1);

namespace App\Core;

use Nette;
use Nette\Application\Routers\RouteList;
use App\UI\FrontModule\Router\RouterFactory as FrontRouterFactory;
use App\UI\UserModule\Router\RouterFactory as UserRouterFactory;

final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;
		$router
			->add(UserRouterFactory::createRouter())
			->add(FrontRouterFactory::createRouter())
			->addRoute('<presenter>/<action>[/<id>]', 'Home:default');
		return $router;
	}
}
