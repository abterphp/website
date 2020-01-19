<?php

declare(strict_types=1);

namespace AbterPhp\Website\Bootstrappers\Template\Loader;

use AbterPhp\Website\Databases\Queries\ContentListCache as Cache;
use AbterPhp\Website\Orm\ContentListItemRepo as ItemRepo;
use AbterPhp\Website\Orm\ContentListRepo as Repo;
use AbterPhp\Website\Template\Builder\ContentList\DefinitionList as DefinitionListBuilder;
use AbterPhp\Website\Template\Builder\ContentList\Simple as SimpleBuilder;
use AbterPhp\Website\Template\Builder\ContentList\WithImage as WithImageBuilder;
use AbterPhp\Website\Template\Loader\ContentList as Loader;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;

class ContentListBootstrapper extends Bootstrapper implements ILazyBootstrapper
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
        $repo     = $container->resolve(Repo::class);
        $itemRepo = $container->resolve(ItemRepo::class);
        $cache    = $container->resolve(Cache::class);

        /** @var DefinitionListBuilder $definitionListBuilder */
        $definitionListBuilder = $container->resolve(DefinitionListBuilder::class);

        /** @var SimpleBuilder $simpleBuilder */
        $simpleBuilder = $container->resolve(SimpleBuilder::class);

        /** @var WithImageBuilder $withImageBuilder */
        $withImageBuilder = $container->resolve(WithImageBuilder::class);

        $builders = [
            $definitionListBuilder->getIdentifier() => $definitionListBuilder,
            $simpleBuilder->getIdentifier()         => $simpleBuilder,
            $withImageBuilder->getIdentifier()      => $withImageBuilder,
        ];

        $loader = new Loader($repo, $itemRepo, $cache, $builders);

        $container->bindInstance(Loader::class, $loader);
    }
}
