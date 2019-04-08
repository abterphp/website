<?php

declare(strict_types=1);

use AbterPhp\Admin\Http\Middleware\Authentication;
use AbterPhp\Admin\Http\Middleware\Authorization;
use AbterPhp\Admin\Http\Middleware\LastGridPage;
use AbterPhp\Framework\Authorization\Constant\Role;
use AbterPhp\Website\Constant\Routes;
use Opulence\Routing\Router;

/**
 * ----------------------------------------------------------
 * Create all of the routes for the HTTP kernel
 * ----------------------------------------------------------
 *
 * @var Router $router
 */
$router->group(
    ['controllerNamespace' => 'AbterPhp\Website\Http\Controllers'],
    function (Router $router) {
        /** @see \AbterPhp\Website\Http\Controllers\Website\Index::homePage() */
        $router->get(Routes::PATH_HOME, 'Website\Index@homePage', [OPTION_NAME => Routes::ROUTE_HOME]);

        /** @see \AbterPhp\Website\Http\Controllers\Website\Index::otherPage() */
        $router->get(
            Routes::PATH_PAGE,
            'Website\Index@otherPage',
            [
                OPTION_NAME => Routes::ROUTE_PAGE_OTHER,
                OPTION_VARS => [Routes::VAR_ANYTHING => '[\w\d\-]+'],
            ]
        );
    }
);
