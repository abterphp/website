<?php

namespace AbterPhp\Website\Template\Builder;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Html\Component;
use AbterPhp\Framework\Template\Data;
use AbterPhp\Framework\Template\IBuilder;
use AbterPhp\Framework\Template\IData;
use AbterPhp\Website\Constant\Event;
use AbterPhp\Website\Constant\Routes;
use AbterPhp\Website\Domain\Entities\Page as Entity;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Routing\Urls\UrlGenerator;

class PageCategory implements IBuilder
{
    const LIST_ITEM_TEMPLATE      = '<li><a href="%s">%s</a></li>';
    const LIST_TEMPLATE           = '<ul>%s</ul>';
    const LIST_CONTAINER_TEMPLATE = '<div class="page-categories"><h2>%s</h2>%s</div>';

    /** @var IEventDispatcher */
    protected $dispatcher;

    /** @var UrlGenerator */
    protected $urlGenerator;

    /**
     * PageCategory constructor.
     *
     * @param IEventDispatcher $dispatcher
     * @param UrlGenerator     $urlGenerator
     */
    public function __construct(IEventDispatcher $dispatcher, UrlGenerator $urlGenerator)
    {
        $this->dispatcher   = $dispatcher;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param Entity[] $pages
     *
     * @return Data
     */
    public function build(array $pages): IData
    {
        $category = $pages[0]->getCategory();

        $body = $this->getCategoryHtml($pages, $category->getName());

        $this->dispatcher->dispatch(Event::PAGE_CATEGORY_READY, $body);

        return new Data(
            $category->getIdentifier(),
            [],
            ['body' => (string)$body]
        );
    }

    /**
     * @param Entity[] $pages
     * @param string   $categoryName
     *
     * @return Component
     */
    protected function getCategoryHtml(array $pages, string $categoryName): Component
    {
        $container = new Component(null, [], [Html5::ATTR_CLASS => 'page-category'], Html5::TAG_DIV);

        if (count($pages) === 0) {
            return $container;
        }

        $list = new Component(null, [], [], Html5::TAG_UL);
        foreach ($pages as $page) {
            $url   = $this->urlGenerator->createFromName(Routes::ROUTE_PAGE_OTHER, $page->getIdentifier());
            $title = $page->getTitle();

            $a = new Component($title, [], [Html5::ATTR_HREF => $url], Html5::TAG_A);

            $list[] = new Component($a, [], [], Html5::TAG_LI);
        }

        $container[] = new Component($pages[0]->getCategory()->getName(), [], [], Html5::TAG_H2);
        $container[] = $list;

        return $container;
    }
}
