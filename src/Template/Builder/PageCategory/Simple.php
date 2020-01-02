<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Builder\PageCategory;

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

class Simple implements IBuilder
{
    const IDENTIFIER = 'simple';

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
     * @return string
     */
    public function getIdentifier(): string
    {
        return static::IDENTIFIER;
    }

    /**
     * @param Entity[] $pages
     *
     * @return Data
     */
    public function build($pages): IData
    {
        if (count($pages) === 0) {
            throw new \InvalidArgumentException();
        }

        if (!$pages[0]->getCategory()) {
            throw new \LogicException();
        }

        $category = $pages[0]->getCategory();

        $body = $this->getCategoryHtml($pages, $category->getName(), $category->getIdentifier());

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
     * @param string   $categoryIdentifier
     *
     * @return Component
     * @throws \Opulence\Routing\Urls\URLException
     */
    protected function getCategoryHtml(array $pages, string $categoryName, string $categoryIdentifier): Component
    {
        $container = new Component(null, [], [Html5::ATTR_CLASS => 'page-category'], Html5::TAG_DIV);

        $list = new Component(null, [], [], Html5::TAG_UL);
        foreach ($pages as $page) {
            // @phan-suppress-next-line PhanTypeMismatchArgument
            $url = $this->urlGenerator->createFromName(Routes::ROUTE_FALLBACK, $page->getIdentifier());
            $a   = new Component($page->getTitle(), [], [Html5::ATTR_HREF => $url], Html5::TAG_A);

            $list[] = new Component($a, [], [], Html5::TAG_LI);
        }

        // @phan-suppress-next-line PhanTypeMismatchArgument
        $url = $this->urlGenerator->createFromName(Routes::ROUTE_FALLBACK, $categoryIdentifier);
        $a   = new Component($categoryName, [], [Html5::ATTR_HREF => $url], Html5::TAG_A);

        $container[] = new Component($a, [], [], Html5::TAG_H2);
        $container[] = $list;

        return $container;
    }
}
