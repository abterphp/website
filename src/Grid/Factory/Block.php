<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Grid\Action\Action;
use AbterPhp\Framework\Grid\Component\Actions;
use AbterPhp\Framework\Grid\Factory\BaseFactory;
use AbterPhp\Framework\Grid\Factory\GridFactory;
use AbterPhp\Framework\Grid\Factory\PaginationFactory as PaginationFactory;
use AbterPhp\Website\Constant\Routes;
use AbterPhp\Website\Grid\Factory\Table\Block as Table;
use AbterPhp\Website\Grid\Filters\Block as Filters;
use Opulence\Routing\Urls\UrlGenerator;

class Block extends BaseFactory
{
    const GROUP_IDENTIFIER = 'block-identifier';
    const GROUP_TITLE      = 'block-title';

    const GETTER_IDENTIFIER = 'getIdentifier';
    const GETTER_TITLE      = 'getTitle';

    /**
     * Block constructor.
     *
     * @param UrlGenerator      $urlGenerator
     * @param PaginationFactory $paginationFactory
     * @param Table             $tableFactory
     * @param GridFactory       $gridFactory
     * @param Filters           $blockFilters
     */
    public function __construct(
        UrlGenerator $urlGenerator,
        PaginationFactory $paginationFactory,
        Table $tableFactory,
        GridFactory $gridFactory,
        Filters $blockFilters
    ) {
        parent::__construct($urlGenerator, $paginationFactory, $tableFactory, $gridFactory, $blockFilters);
    }

    /**
     * @return array
     */
    public function getGetters(): array
    {
        return [
            static::GROUP_IDENTIFIER => static::GETTER_IDENTIFIER,
            static::GROUP_TITLE      => static::GETTER_TITLE,
        ];
    }

    /**
     * @return Actions
     */
    protected function getRowActions(): Actions
    {
        $attributeCallbacks = $this->getAttributeCallbacks();

        $editAttributes   = [
            Html5::ATTR_HREF  => Routes::ROUTE_BLOCKS_EDIT,
        ];
        $deleteAttributes = [
            Html5::ATTR_HREF  => Routes::ROUTE_BLOCKS_DELETE,
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
