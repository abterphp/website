<?php

use AbterPhp\Framework\Constant\Event;
use AbterPhp\Framework\Constant\Module;
use AbterPhp\Framework\Constant\Priorities;
use AbterPhp\Website\Bootstrappers;
use AbterPhp\Website\Events;

return [
    Module::IDENTIFIER         => 'AbterPhp\Website',
    Module::DEPENDENCIES       => ['AbterPhp\Admin'],
    Module::ENABLED            => true,
    Module::BOOTSTRAPPERS      => [
        Bootstrappers\Orm\OrmBootstrapper::class,
        Bootstrappers\Validation\ValidatorBootstrapper::class,
    ],
    Module::CLI_BOOTSTRAPPERS  => [
        Bootstrappers\Database\MigrationsBootstrapper::class,
    ],
    Module::HTTP_BOOTSTRAPPERS => [
        Bootstrappers\Http\Controllers\Website\IndexBootstrapper::class,
        Bootstrappers\Http\Views\BuildersBootstrapper::class,
    ],
    Module::EVENTS             => [
        Event::AUTH_READY            => [
            /** @see \AbterPhp\Website\Events\Listeners\AuthInitializer::handle */
            Priorities::NORMAL => [sprintf('%s@handle', Events\Listeners\AuthInitializer::class)],
        ],
        Event::TEMPLATE_ENGINE_READY => [
            /** @see \AbterPhp\Website\Events\Listeners\TemplateInitializer::handle */
            Priorities::NORMAL => [sprintf('%s@handle', Events\Listeners\TemplateInitializer::class)],
        ],
        Event::NAVIGATION_READY      => [
            /** @see \AbterPhp\Website\Events\Listeners\NavigationBuilder::handle */
            Priorities::NORMAL => [sprintf('%s@handle', Events\Listeners\NavigationBuilder::class)],
        ],
        Event::ENTITY_POST_CHANGE    => [
            /** @see \AbterPhp\Website\Events\Listeners\PageInvalidator::handle */
            Priorities::NORMAL => [sprintf('%s@handle', Events\Listeners\PageInvalidator::class)],
        ],
        Event::DASHBOARD_READY       => [
            /** @see \AbterPhp\Website\Events\Listeners\DashboardBuilder::handle */
            Priorities::NORMAL => [sprintf('%s@handle', Events\Listeners\DashboardBuilder::class)],
        ],
    ],
    Module::ROUTE_PATHS        => [
        Priorities::NORMAL       => [
            __DIR__ . '/admin-routes.php',
            __DIR__ . '/api-routes.php',
        ],
        Priorities::BELOW_NORMAL => [
            __DIR__ . '/website-routes.php',
        ],
    ],
    Module::MIGRATION_PATHS    => [
        Priorities::NORMAL => [
            realpath(__DIR__ . '/src/Databases/Migrations'),
        ],
    ],
    Module::RESOURCE_PATH      => realpath(__DIR__ . '/resources'),
    Module::ASSETS_PATHS       => [
        'root'    => realpath(__DIR__ . '/resources/rawassets'),
        'website' => realpath(__DIR__ . '/resources/rawassets'),
    ],
];
