<?php

declare(strict_types=1);

namespace AbterPhp\Website\Events\Listeners;

use AbterPhp\Framework\Events\NavigationReady;
use AbterPhp\Framework\Html\Component\ButtonFactory;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Navigation\Item;
use AbterPhp\Framework\Navigation\Navigation;
use AbterPhp\Website\Constant\Routes;

class NavigationBuilder
{
    const BASE_WEIGHT = 400;

    /** @var ButtonFactory */
    protected $buttonFactory;

    /**
     * NavigationRegistrar constructor.
     *
     * @param ButtonFactory $buttonFactory
     */
    public function __construct(ButtonFactory $buttonFactory)
    {
        $this->buttonFactory = $buttonFactory;
    }

    /**
     * @param NavigationReady $event
     */
    public function handle(NavigationReady $event)
    {
        $navigation = $event->getNavigation();

        if (!$navigation->hasIntent(Navigation::INTENT_PRIMARY)) {
            return;
        }

        $this->addPage($navigation);
        $this->addPageLayout($navigation);
        $this->addBlock($navigation);
        $this->addBlockLayout($navigation);
    }

    /**
     * @param Navigation $navigation
     */
    protected function addPage(Navigation $navigation)
    {
        $text = 'website:pages';
        $icon = 'text_format';

        $button   = $this->buttonFactory->createFromName($text, Routes::ROUTE_PAGES, [], $icon);
        $resource = $this->getAdminResource(Routes::ROUTE_PAGES);

        $navigation->addItem(new Item($button), static::BASE_WEIGHT, $resource);
    }

    /**
     * @param Navigation $navigation
     */
    protected function addPageLayout(Navigation $navigation)
    {
        $text = 'website:pageLayouts';
        $icon = 'view_quilt';

        $button   = $this->buttonFactory->createFromName($text, Routes::ROUTE_PAGE_LAYOUTS, [], $icon);
        $resource = $this->getAdminResource(Routes::ROUTE_PAGE_LAYOUTS);

        $navigation->addItem(new Item($button), static::BASE_WEIGHT, $resource);
    }

    /**
     * @param Navigation $navigation
     */
    protected function addBlock(Navigation $navigation)
    {
        $text = 'website:blocks';
        $icon = 'view_module';

        $button   = $this->buttonFactory->createFromName($text, Routes::ROUTE_BLOCKS, [], $icon);
        $resource = $this->getAdminResource(Routes::ROUTE_BLOCKS);

        $navigation->addItem(new Item($button), static::BASE_WEIGHT, $resource);
    }

    /**
     * @param Navigation $navigation
     */
    protected function addBlockLayout(Navigation $navigation)
    {
        $text = 'website:blockLayouts';
        $icon = 'view_quilt';

        $button   = $this->buttonFactory->createFromName($text, Routes::ROUTE_BLOCK_LAYOUTS, [], $icon);
        $resource = $this->getAdminResource(Routes::ROUTE_BLOCK_LAYOUTS);

        $navigation->addItem(new Item($button), static::BASE_WEIGHT, $resource);
    }

    /**
     * @param string $resource
     *
     * @return string
     */
    protected function getAdminResource(string $resource): string
    {
        return sprintf('admin_resource_%s', $resource);
    }
}
