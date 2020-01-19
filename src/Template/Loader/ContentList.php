<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Loader;

use AbterPhp\Framework\Template\IBuilder;
use AbterPhp\Framework\Template\IData;
use AbterPhp\Framework\Template\ILoader;
use AbterPhp\Framework\Template\ParsedTemplate;
use AbterPhp\Website\Databases\Queries\ContentListCache as Cache;
use AbterPhp\Website\Domain\Entities\ContentList as Entity;
use AbterPhp\Website\Orm\ContentListItemRepo as ItemRepo;
use AbterPhp\Website\Orm\ContentListRepo as Repo;

class ContentList implements ILoader
{
    use WithBuildersTrait;

    /**
     * @var Repo
     */
    protected $repo;

    /**
     * @var ItemRepo
     */
    protected $itemRepo;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * ContentList constructor.
     *
     * @param Repo       $repo
     * @param ItemRepo   $itemRepo
     * @param Cache      $cache
     * @param IBuilder[] $builders
     */
    public function __construct(Repo $repo, ItemRepo $itemRepo, Cache $cache, array $builders)
    {
        $this->repo     = $repo;
        $this->itemRepo = $itemRepo;
        $this->cache    = $cache;
        $this->builders = $builders;
    }

    /**
     * @param array<string,ParsedTemplate[]> $parsedTemplates
     *
     * @return IData[]
     * @throws \Opulence\Orm\OrmException
     */
    public function load(array $parsedTemplates): array
    {
        $identifiers = array_keys($parsedTemplates);

        $lists = $this->loadWithItems($identifiers);

        $templateData = $this->createTemplateData($parsedTemplates, $lists);

        return $templateData;
    }

    /**
     * @param string[] $identifiers
     *
     * @return array<string,Entity>
     * @throws \Opulence\Orm\OrmException
     */
    protected function loadWithItems(array $identifiers): array
    {
        $lists = $this->repo->getByIdentifiers($identifiers);

        /** @var array<string,Entity> $list */
        $listsById = [];
        foreach ($lists as $list) {
            $listsById[$list->getId()] = $list;
        }

        $items = $this->itemRepo->getByListIds(array_keys($listsById));
        foreach ($items as $item) {
            $listsById[$item->getListId()]->addItem($item);
        }

        $lists = [];
        foreach ($listsById as $list) {
            $lists[$list->getIdentifier()] = $list;
        }

        return $lists;
    }

    /**
     * @param array<string,ParsedTemplate[]> $parsedTemplates
     * @param array<string,Entity>           $lists
     *
     * @return IData[]
     */
    protected function createTemplateData(array $parsedTemplates, array $lists): array
    {
        $templateData = [];

        foreach ($parsedTemplates as $identifier => $identifierTemplates) {
            /** @var ParsedTemplate $parsedTemplate */
            foreach ($identifierTemplates as $parsedTemplate) {
                if (!array_key_exists($identifier, $lists)) {
                    continue;
                }

                /** @var Entity $list */
                $list = $lists[$identifier];

                $templateData[] = $this->buildTemplateData($parsedTemplate, $list, 'simple');
            }
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
