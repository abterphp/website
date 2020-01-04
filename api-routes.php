<?php

declare(strict_types=1);

use AbterPhp\Admin\Config\Routes as RoutesConfig;
use AbterPhp\Admin\Http\Middleware\Api;
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
        $router->group(
            [
                'path' => RoutesConfig::getApiBasePath(),
                'middleware' => [
                    Api::class,
                ],
            ],
            function (Router $router) {
                $entities = [
                    'pages'          => 'Page',
                    'page-layouts'    => 'PageLayout',
                    'page-categories' => 'PageCategory',
                    'blocks'         => 'Block',
                    'block-layouts'   => 'BlockLayout',
                ];

                foreach ($entities as $route => $controllerName) {
                    /** @see \AbterPhp\Website\Http\Controllers\Api\Page::get() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\PageLayout::get() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\PageCategory::get() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\Block::get() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\BlockLayout::get() */
                    $router->get(
                        "/${route}/:entityId",
                        "Api\\${controllerName}@get"
                    );

                    /** @see \AbterPhp\Website\Http\Controllers\Api\Page::list() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\PageLayout::list() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\PageCategory::list() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\Block::list() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\BlockLayout::list() */
                    $router->get(
                        "/${route}",
                        "Api\\${controllerName}@list"
                    );

                    /** @see \AbterPhp\Website\Http\Controllers\Api\Page::create() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\PageLayout::create() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\PageCategory::create() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\Block::create() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\BlockLayout::create() */
                    $router->post(
                        "/${route}",
                        "Api\\${controllerName}@create"
                    );

                    /** @see \AbterPhp\Website\Http\Controllers\Api\Page::update() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\PageLayout::update() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\PageCategory::update() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\Block::update() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\BlockLayout::update() */
                    $router->put(
                        "/${route}/:entityId",
                        "Api\\${controllerName}@update"
                    );

                    /** @see \AbterPhp\Website\Http\Controllers\Api\Page::delete() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\PageLayout::delete() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\PageCategory::delete() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\Block::delete() */
                    /** @see \AbterPhp\Website\Http\Controllers\Api\BlockLayout::delete() */
                    $router->delete(
                        "/${route}/:entityId",
                        "Api\\${controllerName}@delete"
                    );
                }
            }
        );
    }
);
