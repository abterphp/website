<?php

declare(strict_types=1);

namespace AbterPhp\Website\Bootstrappers\Template\Loader;

use AbterPhp\Website\Databases\Queries\ContentListCache as Cache;
use AbterPhp\Website\Orm\ContentListItemRepo as ItemRepo;
use AbterPhp\Website\Orm\ContentListRepo as Repo;
use AbterPhp\Website\Template\Builder\ContentList\Natural as NaturalBuilder;
use AbterPhp\Website\Template\Builder\ContentList\Ordered as OrderedBuilder;
use AbterPhp\Website\Template\Builder\ContentList\Section as SectionBuilder;
use AbterPhp\Website\Template\Builder\ContentList\Unordered as UnorderedBuilder;
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

        /** @var NaturalBuilder $naturalBuilder */
        $naturalBuilder = $container->resolve(NaturalBuilder::class);

        /** @var OrderedBuilder $orderedBuilder */
        $orderedBuilder = $container->resolve(OrderedBuilder::class);

        /** @var SectionBuilder $sectionBuilder */
        $sectionBuilder = $container->resolve(SectionBuilder::class);

        /** @var UnorderedBuilder $unorderedBuilder */
        $unorderedBuilder = $container->resolve(UnorderedBuilder::class);

        $builders = [
            $naturalBuilder->getIdentifier()   => $naturalBuilder,
            $orderedBuilder->getIdentifier()   => $orderedBuilder,
            $sectionBuilder->getIdentifier()   => $sectionBuilder,
            $unorderedBuilder->getIdentifier() => $unorderedBuilder,
        ];

        $loader = new Loader($repo, $itemRepo, $cache, $builders);

        $container->bindInstance(Loader::class, $loader);
    }
}
