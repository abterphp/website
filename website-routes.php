<?php

declare(strict_types=1);

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
        /** @see \AbterPhp\Website\Http\Controllers\Website\Index::index() */
        $router->get(Routes::PATH_INDEX, 'Website\Index@index', [OPTION_NAME => Routes::ROUTE_INDEX]);

        /** @see \AbterPhp\Website\Http\Controllers\Website\Index::fallback() */
        $router->get(
            Routes::PATH_FALLBACK,
            'Website\Index@fallback',
            [
                OPTION_NAME => Routes::ROUTE_FALLBACK,
                OPTION_VARS => [Routes::VAR_ANYTHING => '[\w\d\-]+'],
            ]
        );
    }
);
