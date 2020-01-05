<?php

declare(strict_types=1);

use AbterPhp\Website\Constant\Route as RouteConstant;
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
            '/',
            'Website\Index@index',
            [RouteConstant::OPTION_NAME => RouteConstant::INDEX]
        );

        /** @see \AbterPhp\Website\Http\Controllers\Website\Index::fallback() */
        $router->get(
            '/:identifier',
            'Website\Index@fallback',
            [
                RouteConstant::OPTION_NAME => RouteConstant::FALLBACK,
                RouteConstant::OPTION_VARS => [RouteConstant::VAR_ANYTHING => '[\w\d\-]+'],
            ]
        );
    }
);
