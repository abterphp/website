<?php

declare(strict_types=1);

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
                'path'       => PATH_API,
                'middleware' => [
                    Api::class,
                ],
            ],
            function (Router $router) {
                $entities = [
                    'pages'          => 'Page',
                    'pagelayouts'    => 'PageLayout',
                    'pagecategories' => 'PageCategory',
                    'blocks'         => 'Block',
                    'blocklayouts'   => 'BlockLayout',
                ];

                foreach ($entities as $route => $controllerName) {
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\Page::get() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\PageLayout::get() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\PageCategory::get() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\Block::get() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\BlockLayout::get() */
                    $router->get(
                        "/${route}/:entityId",
                        "Api\\${controllerName}@get"
                    );

                    /** @see \AbterPhp\Admin\Http\Controllers\Api\Page::list() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\PageLayout::list() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\PageCategory::list() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\Block::list() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\BlockLayout::list() */
                    $router->get(
                        "/${route}",
                        "Api\\${controllerName}@list"
                    );

                    /** @see \AbterPhp\Admin\Http\Controllers\Api\Page::create() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\PageLayout::create() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\PageCategory::create() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\Block::create() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\BlockLayout::create() */
                    $router->post(
                        "/${route}",
                        "Api\\${controllerName}@create"
                    );

                    /** @see \AbterPhp\Admin\Http\Controllers\Api\Page::update() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\PageLayout::update() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\PageCategory::update() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\Block::update() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\BlockLayout::update() */
                    $router->put(
                        "/${route}/:entityId",
                        "Api\\${controllerName}@update"
                    );

                    /** @see \AbterPhp\Admin\Http\Controllers\Api\Page::delete() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\PageLayout::delete() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\PageCategory::delete() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\Block::delete() */
                    /** @see \AbterPhp\Admin\Http\Controllers\Api\BlockLayout::delete() */
                    $router->delete(
                        "/${route}/:entityId",
                        "Api\\${controllerName}@delete"
                    );
                }
            }
        );
    }
);
