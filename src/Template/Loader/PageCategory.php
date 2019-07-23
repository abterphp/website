<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Loader;

use AbterPhp\Framework\Template\IBuilder;
use AbterPhp\Framework\Template\IData;
use AbterPhp\Framework\Template\ILoader;
use AbterPhp\Website\Databases\Queries\PageCategoryCache;
use AbterPhp\Website\Domain\Entities\Page;
use AbterPhp\Website\Orm\PageRepo;

class PageCategory implements ILoader
{
    /**
     * @var PageRepo
     */
    protected $pageRepo;

    /**
     * @var PageCategoryCache
     */
    protected $pageCategoryCache;

    /**
     * @var IBuilder
     */
    protected $builder;

    /**
     * PageCategoryLoader constructor.
     *
     * @param PageRepo          $pageRepo
     * @param PageCategoryCache $blockCache
     * @param IBuilder          $builder
     */
    public function __construct(PageRepo $pageRepo, PageCategoryCache $pageCategoryCache, IBuilder $builder)
    {
        $this->pageRepo          = $pageRepo;
        $this->pageCategoryCache = $pageCategoryCache;
        $this->builder           = $builder;
    }

    /**
     * @param string[] $identifiers
     *
     * @return IData[]
     */
    public function load(array $identifiers): array
    {
        $pages = $this->pageRepo->getByCategoryIdentifiers($identifiers);

        $titlesByCategories = $this->groupPages($pages);

        $templateData = [];
        foreach ($titlesByCategories as $category => $pages) {
            $templateData[] = $this->builder->build($pages);
        }

        return $templateData;
    }

    /**
     * @param array $pages
     *
     * @return Page[][]
     */
    protected function groupPages(array $pages): array
    {
        $titlesByCategories = [];
        foreach ($pages as $page) {
            $titlesByCategories[$page->getCategory()->getIdentifier()][] = $page;
        }

        return $titlesByCategories;
    }

    /**
     * @param string[] $identifiers
     * @param string   $cacheTime
     *
     * @return bool
     */
    public function hasAnyChangedSince(array $identifiers, string $cacheTime): bool
    {
        return $this->pageCategoryCache->hasAnyChangedSince($identifiers, $cacheTime);
    }
}
