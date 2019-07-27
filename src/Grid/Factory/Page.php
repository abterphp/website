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
use AbterPhp\Website\Domain\Entities\Page as Entity;
use AbterPhp\Website\Grid\Factory\Table\Page as Table;
use AbterPhp\Website\Grid\Filters\Page as Filters;
use Opulence\Routing\Urls\UrlGenerator;

class Page extends BaseFactory
{
    const GROUP_IDENTIFIER   = 'page-identifier';
    const GROUP_TITLE        = 'page-title';
    const GROUP_CATEGORY     = 'page-category';
    const GROUP_IS_PUBLISHED = 'page-isPublished';

    const GETTER_IDENTIFIER   = 'getIdentifier';
    const GETTER_TITLE        = 'getTitle';
    const GETTER_CATEGORY     = 'getCategory';
    const GETTER_IS_PUBLISHED = 'pageIsPublished';

    /**
     * Page constructor.
     *
     * @param UrlGenerator      $urlGenerator
     * @param PaginationFactory $paginationFactory
     * @param Table             $tableFactory
     * @param GridFactory       $gridFactory
     * @param Filters           $filters
     */
    public function __construct(
        UrlGenerator $urlGenerator,
        PaginationFactory $paginationFactory,
        Table $tableFactory,
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
            static::GROUP_IDENTIFIER   => static::GETTER_IDENTIFIER,
            static::GROUP_TITLE        => static::GETTER_TITLE,
            static::GROUP_CATEGORY     => [$this, 'getCategoryName'],
            static::GROUP_IS_PUBLISHED => [$this, 'isPublished'],
        ];
    }

    /**
     * @param Entity $entity
     *
     * @return string
     */
    public function getCategoryName(Entity $entity): string
    {
        if ($entity->getCategory()) {
            return $entity->getCategory()->getName();
        }

        return '';
    }

    /**
     * @param Entity $entity
     *
     * @return string
     */
    public function isPublished(Entity $entity): string
    {
        if ($entity->isDraft()) {
            return '<i class="material-icons pmd-md is-danger">warning</i>';
        }

        return '<i class="material-icons pmd-md is-success">check circle</i>';
    }

    /**
     * @return Actions
     */
    protected function getRowActions(): Actions
    {
        $attributeCallbacks = $this->getAttributeCallbacks();

        $editAttributes   = [
            Html5::ATTR_HREF => Routes::ROUTE_PAGES_EDIT,
        ];
        $deleteAttributes = [
            Html5::ATTR_HREF => Routes::ROUTE_PAGES_DELETE,
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
