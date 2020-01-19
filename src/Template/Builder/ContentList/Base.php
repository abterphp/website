<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Builder\ContentList;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Template\IBuilder;
use AbterPhp\Framework\Template\ParsedTemplate;

class Base
{
    protected $defaultListTag    = Html5::TAG_UL;
    protected $defaultItemTag    = Html5::TAG_LI;
    protected $defaultLabelTag   = '';
    protected $defaultContentTag = '';
    protected $defaultImageTag   = '';

    protected $defaultListClass    = 'list-unknown';
    protected $defaultItemClass    = 'list-item';
    protected $defaultLabelClass   = 'list-item-label';
    protected $defaultContentClass = 'list-item-content';
    protected $defaultImageClass   = 'list-item-image';

    protected $defaultWithLabel = '0';
    protected $defaultWithImage = '0';

    /**
     * @param ParsedTemplate|null $template
     *
     * @return array<string,string>
     */
    protected function getWrapperTags(?ParsedTemplate $template = null): array
    {
        if (!$template) {
            return [
                IBuilder::LIST_TAG    => $this->defaultListTag,
                IBuilder::ITEM_TAG    => $this->defaultItemTag,
                IBuilder::LABEL_TAG   => $this->defaultLabelTag,
                IBuilder::CONTENT_TAG => $this->defaultContentTag,
                IBuilder::IMAGE_TAG   => $this->defaultImageTag,
            ];
        }

        $listTag    = $template->getAttribute(IBuilder::LIST_TAG, $this->defaultListTag);
        $itemTag    = $template->getAttribute(IBuilder::ITEM_TAG, $this->defaultItemTag);
        $labelTag   = $template->getAttribute(IBuilder::LABEL_TAG, $this->defaultLabelTag);
        $contentTag = $template->getAttribute(IBuilder::CONTENT_TAG, $this->defaultContentTag);
        $imageTag   = $template->getAttribute(IBuilder::IMAGE_TAG, $this->defaultImageTag);

        return [
            IBuilder::LIST_TAG    => $listTag,
            IBuilder::ITEM_TAG    => $itemTag,
            IBuilder::LABEL_TAG   => $labelTag,
            IBuilder::CONTENT_TAG => $contentTag,
            IBuilder::IMAGE_TAG   => $imageTag,
        ];
    }

    /**
     * @param ParsedTemplate|null $template
     *
     * @return array<string,string>
     */
    protected function getWrapperClasses(?ParsedTemplate $template = null): array
    {
        if (!$template) {
            return [
                IBuilder::LIST_CLASS    => $this->defaultListClass,
                IBuilder::ITEM_CLASS    => $this->defaultItemClass,
                IBuilder::LABEL_CLASS   => $this->defaultLabelClass,
                IBuilder::CONTENT_CLASS => $this->defaultContentClass,
                IBuilder::IMAGE_CLASS   => $this->defaultImageClass,
            ];
        }

        $listClass    = $template->getAttribute(IBuilder::LIST_CLASS, $this->defaultListClass);
        $itemClass    = $template->getAttribute(IBuilder::ITEM_CLASS, $this->defaultItemClass);
        $labelClass   = $template->getAttribute(IBuilder::LABEL_CLASS, $this->defaultLabelClass);
        $contentClass = $template->getAttribute(IBuilder::CONTENT_CLASS, $this->defaultContentClass);
        $imageClass   = $template->getAttribute(IBuilder::IMAGE_CLASS, $this->defaultImageClass);

        return [
            IBuilder::LIST_CLASS    => $listClass,
            IBuilder::ITEM_CLASS    => $itemClass,
            IBuilder::LABEL_CLASS   => $labelClass,
            IBuilder::CONTENT_CLASS => $contentClass,
            IBuilder::IMAGE_CLASS   => $imageClass,
        ];
    }

    /**
     * @param ParsedTemplate|null $template
     *
     * @return array<string,string>
     */
    protected function getOptions(?ParsedTemplate $template = null): array
    {
        if (!$template) {
            return [
                IBuilder::WITH_LABEL_OPTION => (bool)$this->defaultWithLabel,
                IBuilder::WITH_IMAGE_OPTION => (bool)$this->defaultWithImage,
            ];
        }

        $listClass = $template->getAttribute(IBuilder::WITH_LABEL_OPTION, $this->defaultWithLabel);
        $itemClass = $template->getAttribute(IBuilder::WITH_IMAGE_OPTION, $this->defaultWithImage);

        return [
            IBuilder::WITH_LABEL_OPTION => (bool)$listClass,
            IBuilder::WITH_IMAGE_OPTION => (bool)$itemClass,
        ];
    }
}
