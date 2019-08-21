<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory;

use AbterPhp\Admin\Grid\Factory\BaseFactory;
use AbterPhp\Admin\Grid\Factory\GridFactory;
use AbterPhp\Admin\Grid\Factory\PaginationFactory;
use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Grid\Action\Action;
use AbterPhp\Framework\Grid\Component\Actions;
use AbterPhp\Website\Constant\Routes;
use AbterPhp\Website\Grid\Factory\Table\PageCategory as TableFactory;
use AbterPhp\Website\Grid\Filters\PageCategory as Filters;
use Opulence\Routing\Urls\UrlGenerator;

class PageCategory extends BaseFactory
{
    const GROUP_IDENTIFIER = 'pageCategory-identifier';
    const GROUP_NAME       = 'pageCategory-name';

    const GETTER_IDENTIFIER = 'getIdentifier';

    /**
     * PageCategory constructor.
     *
     * @param UrlGenerator      $urlGenerator
     * @param PaginationFactory $paginationFactory
     * @param TableFactory      $tableFactory
     * @param GridFactory       $gridFactory
     * @param Filters           $filters
     */
    public function __construct(
        UrlGenerator $urlGenerator,
        PaginationFactory $paginationFactory,
        TableFactory $tableFactory,
        GridFactory $gridFactory,
        Filters $filters
    ) {
        parent::__construct($urlGenerator, $paginationFactory, $tableFactory, $gridFactory, $filters);
    }

    /**
     * @return array
     */
    public function getGetters(): array
    {
        return [
            static::GROUP_IDENTIFIER => static::GETTER_IDENTIFIER,
        ];
    }

    /**
     * @return Actions
     */
    protected function getRowActions(): Actions
    {
        $attributeCallbacks = $this->getAttributeCallbacks();

        $editAttributes   = [
            Html5::ATTR_HREF  => Routes::ROUTE_PAGE_CATEGORIES_EDIT,
        ];
        $deleteAttributes = [
            Html5::ATTR_HREF  => Routes::ROUTE_PAGE_CATEGORIES_DELETE,
        ];

        $cellActions   = new Actions();
        $cellActions[] = new Action(
            static::LABEL_EDIT,
            $this->editIntents,
            $editAttributes,
            $attributeCallbacks,
            Html5::TAG_A
        );
        $cellActions[] = new Action(
            static::LABEL_DELETE,
            $this->deleteIntents,
            $deleteAttributes,
            $attributeCallbacks,
            Html5::TAG_A
        );

        return $cellActions;
    }
}
