<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Loader;

use AbterPhp\Framework\Template\IData;
use AbterPhp\Framework\Template\ILoader;
use AbterPhp\Framework\Template\Data;
use AbterPhp\Framework\Template\ParsedTemplate;
use AbterPhp\Website\Databases\Queries\BlockCache;
use AbterPhp\Website\Orm\BlockRepo;

class Block implements ILoader
{
    /**
     * @var BlockRepo
     */
    protected $repo;

    /**
     * @var BlockCache
     */
    protected $cache;

    /**
     * BlockLoader constructor.
     *
     * @param BlockRepo  $repo
     * @param BlockCache $cache
     */
    public function __construct(BlockRepo $repo, BlockCache $cache)
    {
        $this->repo  = $repo;
        $this->cache = $cache;
    }

    /**
     * @param ParsedTemplate[][] $parsedTemplates
     *
     * @return IData[]
     */
    public function load(array $parsedTemplates): array
    {
        $identifiers = array_keys($parsedTemplates);

        $blocks = $this->repo->getWithLayoutByIdentifiers($identifiers);

        $templateData = [];
        foreach ($blocks as $block) {
            $templateData[] = new Data(
                $block->getIdentifier(),
                ['title' => $block->getTitle()],
                ['body' => $block->getBody(), 'layout' => $block->getLayout()]
            );
        }

        return $templateData;
    }

    /**
     * @param string[] $identifiers
     * @param string   $cacheTime
     *
     * @return bool
     */
    public function hasAnyChangedSince(array $identifiers, string $cacheTime): bool
    {
        return $this->cache->hasAnyChangedSince($identifiers, $cacheTime);
    }
}
