<?php

declare(strict_types=1);

namespace AbterPhp\Website\Bootstrappers\Database;

use AbterPhp\Admin\Bootstrappers\Database\MigrationsBootstrapper as AdminBootstrapper;
use AbterPhp\Admin\Bootstrappers\Filesystem\FileFinderBootstrapper;
use AbterPhp\Framework\Filesystem\IFileFinder; // @phan-suppress-current-line PhanUnreferencedUseNormal
use AbterPhp\Website\Databases\Migrations\Init;
use AbterPhp\Website\Databases\Migrations\WebsiteLists;
use Opulence\Databases\IConnection;
use Opulence\Ioc\IContainer;

class MigrationsBootstrapper extends AdminBootstrapper
{
    /**
     * @return array
     */
    public function getBindings(): array
    {
        return [
            Init::class,
            WebsiteLists::class,
        ];
    }

    /**
     * @param IContainer $container
     *
     * @throws \Opulence\Ioc\IocException
     */
    public function registerBindings(IContainer $container)
    {
        /** @var IConnection $connection */
        $connection = $container->resolve(IConnection::class);

        /** @var IFileFinder $fileFinder */
        $fileFinder = $container->resolve(FileFinderBootstrapper::MIGRATION_FILE_FINDER);

        $container->bindInstance(Init::class, new Init($connection, $fileFinder));
        $container->bindInstance(WebsiteLists::class, new WebsiteLists($connection, $fileFinder));
    }
}
