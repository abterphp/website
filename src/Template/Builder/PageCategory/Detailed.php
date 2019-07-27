<?php

namespace AbterPhp\Website\Template\Builder\PageCategory;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Html\Component;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Template\Data;
use AbterPhp\Framework\Template\IBuilder;
use AbterPhp\Framework\Template\IData;
use AbterPhp\Website\Constant\Event;
use AbterPhp\Website\Constant\Routes;
use AbterPhp\Website\Domain\Entities\Page as Entity;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Routing\Urls\UrlGenerator;

class Detailed implements IBuilder
{
    const IDENTIFIER = 'detailed';

    const LIST_ITEM_TEMPLATE      = '<li><a href="%s">%s</a></li>';
    const LIST_TEMPLATE           = '<ul>%s</ul>';
    const LIST_CONTAINER_TEMPLATE = '<div class="page-categories"><h2>%s</h2>%s</div>';

    const MORE_BTN_CONTAINER_CLASS = 'more-btn-container';

    /** @var IEventDispatcher */
    protected $dispatcher;

    /** @var UrlGenerator */
    protected $urlGenerator;

    /** @var ITranslator */
    protected $translator;

    /**
     * PageCategory constructor.
     *
     * @param IEventDispatcher $dispatcher
     * @param UrlGenerator     $urlGenerator
     * @param ITranslator      $translator
     */
    public function __construct(IEventDispatcher $dispatcher, UrlGenerator $urlGenerator, ITranslator $translator)
    {
        $this->dispatcher   = $dispatcher;
        $this->urlGenerator = $urlGenerator;
        $this->translator   = $translator;
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
    public function build(array $pages): IData
    {
        $category = $pages[0]->getCategory();

        $body = $this->buildCategory($pages, $category->getName(), $category->getIdentifier());

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
     */
    protected function buildCategory(array $pages, string $categoryName, string $categoryIdentifier): Component
    {
        $container = new Component(null, [], [Html5::ATTR_CLASS => 'page-category'], Html5::TAG_SECTION);

        if (count($pages) === 0) {
            return $container;
        }

        $list = new Component(null, [], [Html5::ATTR_CLASS => 'page-container'], Html5::TAG_DIV);
        foreach ($pages as $page) {
            $list[] = $this->buildPage($page);
        }

        $url = $this->urlGenerator->createFromName(Routes::ROUTE_FALLBACK, $categoryIdentifier);
        $a   = new Component($categoryName, [], [Html5::ATTR_HREF => $url], Html5::TAG_A);

        $container[] = new Component($a, [], [], Html5::TAG_H2);
        $container[] = $list;

        return $container;
    }

    /**
     * @param Entity $page
     *
     * @return Component
     */
    protected function buildPage(Entity $page): Component
    {
        $item = new Component(null, [], [], Html5::TAG_ARTICLE);

        $url = $this->urlGenerator->createFromName(Routes::ROUTE_FALLBACK, $page->getIdentifier());

        $item[] = $this->buildPageTitle($page, $url);
        $item[] = $this->buildPageLead($page);
        $item[] = $this->buildPageButtons($url);

        return $item;
    }

    /**
     * @param Entity $page
     * @param string $url
     *
     * @return Component
     */
    protected function buildPageTitle(Entity $page, string $url): Component
    {
        $a = new Component($page->getTitle(), [], [Html5::ATTR_HREF => $url], Html5::TAG_A);

        return new Component($a, [], [], Html5::TAG_H3);
    }

    /**
     * @param Entity $page
     *
     * @return Component
     */
    protected function buildPageLead(Entity $page): Component
    {
        $lead = new Component(null, [], [], Html5::TAG_DIV);
        foreach (explode("\n", $page->getLead()) as $paragraph) {
            if (trim($paragraph) === '') {
                continue;
            }

            $lead[] = new Component($paragraph, [], [], Html5::TAG_P);
        }

        return $lead;
    }

    /**
     * @param string $url
     *
     * @return Component
     */
    protected function buildPageButtons(string $url): Component
    {
        $iconHtml = '<i class="fas fa-angle-right"></i>';
        $aContent = sprintf('%s&nbsp;%s', $this->translator->translate('website:more'), $iconHtml);

        $a       = new Component($aContent, [], [Html5::ATTR_HREF => $url], Html5::TAG_A);
        $p       = new Component($a, [], [], Html5::TAG_P);
        $buttons = new Component($p, [], [Html5::ATTR_CLASS => static::MORE_BTN_CONTAINER_CLASS], Html5::TAG_DIV);

        return $buttons;
    }
}