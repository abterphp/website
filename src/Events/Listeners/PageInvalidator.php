<?php

declare(strict_types=1);

namespace AbterPhp\Website\Events\Listeners;

use AbterPhp\Framework\Events\EntityChange;
use AbterPhp\Framework\Template\CacheManager;
use AbterPhp\Website\Domain\Entities\Block;
use AbterPhp\Website\Domain\Entities\Page;
use AbterPhp\Website\Domain\Entities\PageLayout;

class PageInvalidator
{
    /** @var CacheManager */
    protected $cacheManager;

    /**
     * AuthInvalidator constructor.
     *
     * @param CacheManager $cacheManager
     */
    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * @param EntityChange $event
     */
    public function handle(EntityChange $event)
    {
        switch ($event->getEntityName()) {
            case Page::class:
            case PageLayout::class:
            case Block::class:
                $this->cacheManager->flush();
                break;
        }
    }
}
