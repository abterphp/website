<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory;

use AbterPhp\Admin\Grid\Factory\BaseFactory;
use AbterPhp\Admin\Grid\Factory\GridFactory;
use AbterPhp\Admin\Grid\Factory\PaginationFactory;
use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Grid\Action\Action;
use AbterPhp\Framework\Grid\Component\Actions;
use AbterPhp\Website\Constant\Route;
use AbterPhp\Website\Domain\Entities\Page as Entity;
use AbterPhp\Website\Grid\Factory\Table\Header\Page as HeaderFactory;
use AbterPhp\Website\Grid\Factory\Table\Page as TableFactory;
use AbterPhp\Website\Grid\Filters\Page as Filters;
use Opulence\Routing\Urls\UrlGenerator;

class Page extends BaseFactory
{
    public const LAYOUT_OKAY    = 'OK';
    public const LAYOUT_MISSING = '!';

    public const TARGET_PREVIEW = 'page-preview';

    private const GETTER_TITLE = 'getTitle';

    /**
     * Page constructor.
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
            HeaderFactory::GROUP_TITLE        => static::GETTER_TITLE,
            HeaderFactory::GROUP_CATEGORY     => [$this, 'getCategoryName'],
            HeaderFactory::GROUP_LAYOUT       => [$this, 'getLayout'],
            HeaderFactory::GROUP_IS_PUBLISHED => [$this, 'isPublished'],
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

        return '<i class="material-icons pmd-sm">remove</i>';
    }

    /**
     * @param Entity $entity
     *
     * @return string
     */
    public function getLayout(Entity $entity): string
    {
        if ($entity->getLayoutId()) {
            return $entity->getLayout();
        }

        if ($entity->getLayout()) {
            return '<i class="material-icons pmd-sm">remove</i>';
        }

        return '<i class="material-icons is-danger pmd-sm">warning</i>';
    }

    /**
     * @param Entity $entity
     *
     * @return string
     */
    public function isPublished(Entity $entity): string
    {
        if ($entity->isDraft()) {
            return '<i class="material-icons is-danger pmd-sm">warning</i>';
        }

        return '<i class="material-icons is-success pmd-sm">check circle</i>';
    }

    /**
     * @return Actions
     */
    protected function getRowActions(): Actions
    {
        $attributeCallbacks = $this->getAttributeCallbacks();

        $editAttributes   = [
            Html5::ATTR_HREF => Route::PAGES_EDIT,
        ];
        $deleteAttributes = [
            Html5::ATTR_HREF => Route::PAGES_DELETE,
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
        $cellActions[] = new Action(
            static::LABEL_VIEW,
            $this->viewIntents,
            [Html5::ATTR_TARGET => static::TARGET_PREVIEW],
            $this->getViewAttributeCallbacks(),
            Html5::TAG_A
        );

        return $cellActions;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @return callable[]
     */
    protected function getViewAttributeCallbacks(): array
    {
        $urlGenerator = $this->urlGenerator;

        // @suppress PhanUnusedVariable
        $hrefClosure = function ($attribute, Entity $entity) use ($urlGenerator) {
            // @suppress PhanTypeMismatchArgument
            return $urlGenerator->createFromName(Route::FALLBACK, $entity->getIdentifier());
        };

        $attributeCallbacks = [
            Html5::ATTR_HREF => $hrefClosure,
        ];

        return $attributeCallbacks;
    }
}
