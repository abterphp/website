<?php

declare(strict_types=1);

namespace AbterPhp\Website\Events\Listeners;

use AbterPhp\Framework\Events\TemplateEngineReady;
use AbterPhp\Website\Template\Loader\Block as BlockLoader;
use AbterPhp\Website\Template\Loader\PageCategory as PageCategoryLoader;

class TemplateInitializer
{
    const TEMPLATE_TYPE_BLOCK         = 'block';
    const TEMPLATE_TYPE_PAGE_CATEGORY = 'page-category';

    /** @var BlockLoader */
    protected $blockLoader;

    /** @var PageCategoryLoader */
    protected $pageCategoryLoader;

    /**
     * TemplateInitializer constructor.
     *
     * @param BlockLoader        $blockLoader
     * @param PageCategoryLoader $pageCategoryLoader
     */
    public function __construct(BlockLoader $blockLoader, PageCategoryLoader $pageCategoryLoader)
    {
        $this->blockLoader        = $blockLoader;
        $this->pageCategoryLoader = $pageCategoryLoader;
    }

    /**
     * @param TemplateEngineReady $event
     */
    public function handle(TemplateEngineReady $event)
    {
        $event->getEngine()
            ->getRenderer()
            ->addLoader(static::TEMPLATE_TYPE_BLOCK, $this->blockLoader)
            ->addLoader(static::TEMPLATE_TYPE_PAGE_CATEGORY, $this->pageCategoryLoader);
    }
}
