<?php

declare(strict_types=1);

namespace AbterPhp\Website\Bootstrappers\Template;

use AbterPhp\Website\Databases\Queries\PageCategoryCache;
use AbterPhp\Website\Orm\PageRepo;
use AbterPhp\Website\Template\Builder\PageCategory;
use AbterPhp\Website\Template\PageCategoryLoader;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;

class PageCategoryLoaderBootstrapper extends Bootstrapper implements ILazyBootstrapper
{
    /**
     * @return array
     */
    public function getBindings(): array
    {
        return [
            PageCategoryLoader::class,
        ];
    }

    /**
     * @param IContainer $container
     */
    public function registerBindings(IContainer $container)
    {
        $pageCategoryCache = $container->resolve(PageCategoryCache::class);
        $pageRepo          = $container->resolve(PageRepo::class);
        $builder           = $container->resolve(PageCategory::class);

        $loader = new PageCategoryLoader($pageRepo, $pageCategoryCache, $builder);

        $container->bindInstance(PageCategoryLoader::class, $loader);
    }
}
