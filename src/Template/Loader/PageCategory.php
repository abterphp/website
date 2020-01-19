<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Loader;

use AbterPhp\Framework\Template\IBuilder;
use AbterPhp\Framework\Template\IData;
use AbterPhp\Framework\Template\ILoader;
use AbterPhp\Framework\Template\ParsedTemplate;
use AbterPhp\Website\Databases\Queries\PageCategoryCache;
use AbterPhp\Website\Domain\Entities\Page;
use AbterPhp\Website\Orm\PageRepo;
use Opulence\Orm\OrmException;
use Opulence\QueryBuilders\InvalidQueryException;

class PageCategory implements ILoader
{
    use WithBuildersTrait;

    /**
     * @var PageRepo
     */
    protected $pageRepo;

    /**
     * @var PageCategoryCache
     */
    protected $cache;

    /**
     * PageCategory constructor.
     *
     * @param PageRepo          $pageRepo
     * @param PageCategoryCache $pageCategoryCache
     * @param IBuilder[]        $builders
     */
    public function __construct(PageRepo $pageRepo, PageCategoryCache $pageCategoryCache, array $builders)
    {
        $this->pageRepo = $pageRepo;
        $this->cache    = $pageCategoryCache;
        $this->builders = $builders;
    }

    /**
     * @param array<string,ParsedTemplate[]> $parsedTemplates
     *
     * @return IData[]
     * @throws OrmException
     */
    public function load(array $parsedTemplates): array
    {
        $identifiers = array_keys($parsedTemplates);

        $groupedPages = $this->loadPages($identifiers);

        $templateData = $this->createTemplateData($parsedTemplates, $groupedPages);

        return $templateData;
    }

    /**
     * @param string[] $identifiers
     *
     * @return array<string,Page[]>
     */
    protected function loadPages(array $identifiers): array
    {
        $pages = $this->pageRepo->getByCategoryIdentifiers($identifiers);

        $groupedPages = [];
        foreach ($pages as $page) {
            $groupedPages[$page->getCategory()->getIdentifier()][] = $page;
        }

        return $groupedPages;
    }

    /**
     * @param array<string,ParsedTemplate[]> $parsedTemplates
     * @param Page[][]                       $groupedPages
     *
     * @return IData[]
     */
    protected function createTemplateData(array $parsedTemplates, array $groupedPages): array
    {
        $templateData = [];
        foreach ($parsedTemplates as $identifier => $identifierTemplates) {
            /** @var ParsedTemplate $parsedTemplate */
            foreach ($identifierTemplates as $parsedTemplate) {
                if (!array_key_exists($identifier, $groupedPages)) {
                    continue;
                }

                $pages = $groupedPages[$identifier];

                $templateData[] = $this->buildTemplateData($parsedTemplate, $pages, 'simple');
            }
        }

        return $templateData;
    }

    /**
     * @param string[] $identifiers
     * @param string   $cacheTime
     *
     * @return bool
     * @throws InvalidQueryException
     */
    public function hasAnyChangedSince(array $identifiers, string $cacheTime): bool
    {
        return $this->cache->hasAnyChangedSince($identifiers, $cacheTime);
    }
}
