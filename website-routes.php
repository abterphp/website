<?php

declare(strict_types=1);

use AbterPhp\Website\Constant\Routes as RoutesConstant;
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
        $router->get(
            RoutesConstant::PATH_INDEX,
            'Website\Index@index',
            [RoutesConstant::OPTION_NAME => RoutesConstant::ROUTE_INDEX]
        );

        /** @see \AbterPhp\Website\Http\Controllers\Website\Index::fallback() */
        $router->get(
            RoutesConstant::PATH_FALLBACK,
            'Website\Index@fallback',
            [
                RoutesConstant::OPTION_NAME => RoutesConstant::ROUTE_FALLBACK,
                RoutesConstant::OPTION_VARS => [RoutesConstant::VAR_ANYTHING => '[\w\d\-]+'],
            ]
        );
    }
);
