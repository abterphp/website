<?php

declare(strict_types=1);

namespace AbterPhp\Website\Bootstrappers\Template\Loader;

use AbterPhp\Website\Databases\Queries\PageCategoryCache as Cache;
use AbterPhp\Website\Orm\PageRepo as Repo;
use AbterPhp\Website\Template\Builder\PageCategory as Builder;
use AbterPhp\Website\Template\Loader\PageCategory as Loader;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;

class PageCategoryBootstrapper extends Bootstrapper implements ILazyBootstrapper
{
    /**
     * @return array
     */
    public function getBindings(): array
    {
        return [
            Loader::class,
        ];
    }

    /**
     * @param IContainer $container
     */
    public function registerBindings(IContainer $container)
    {
        $cache   = $container->resolve(Cache::class);
        $repo    = $container->resolve(Repo::class);
        $builder = $container->resolve(Builder::class);

        $loader = new Loader($repo, $cache, $builder);

        $container->bindInstance(Loader::class, $loader);
    }
}
