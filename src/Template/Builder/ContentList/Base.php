<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Builder\ContentList;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Template\ParsedTemplate;

class Base
{
    const LIST_TAG    = 'list-tag';
    const ITEM_TAG    = 'item-tag';
    const LABEL_TAG   = 'label-tag';
    const CONTENT_TAG = 'content-tag';
    const IMAGE_TAG   = 'image-tag';

    const LIST_CLASS    = 'list-class';
    const ITEM_CLASS    = 'item-class';
    const LABEL_CLASS   = 'label-class';
    const CONTENT_CLASS = 'content-class';
    const IMAGE_CLASS   = 'image-class';

    const WITH_LABEL_OPTION  = 'with-label';
    const WITH_IMAGES_OPTION = 'with-images';

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
                IContentList::LIST_TAG    => $this->defaultListTag,
                IContentList::ITEM_TAG    => $this->defaultItemTag,
                IContentList::LABEL_TAG   => $this->defaultLabelTag,
                IContentList::CONTENT_TAG => $this->defaultContentTag,
                IContentList::IMAGE_TAG   => $this->defaultImageTag,
            ];
        }

        $listTag    = $template->getAttribute(IContentList::LIST_TAG, $this->defaultListTag);
        $itemTag    = $template->getAttribute(IContentList::ITEM_TAG, $this->defaultItemTag);
        $labelTag   = $template->getAttribute(IContentList::LABEL_TAG, $this->defaultLabelTag);
        $contentTag = $template->getAttribute(IContentList::CONTENT_TAG, $this->defaultContentTag);
        $imageTag   = $template->getAttribute(IContentList::IMAGE_TAG, $this->defaultImageTag);

        return [
            IContentList::LIST_TAG    => $listTag,
            IContentList::ITEM_TAG    => $itemTag,
            IContentList::LABEL_TAG   => $labelTag,
            IContentList::CONTENT_TAG => $contentTag,
            IContentList::IMAGE_TAG   => $imageTag,
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
                IContentList::LIST_CLASS    => $this->defaultListClass,
                IContentList::ITEM_CLASS    => $this->defaultItemClass,
                IContentList::LABEL_CLASS   => $this->defaultLabelClass,
                IContentList::CONTENT_CLASS => $this->defaultContentClass,
                IContentList::IMAGE_CLASS   => $this->defaultImageClass,
            ];
        }

        $listClass    = $template->getAttribute(IContentList::LIST_CLASS, $this->defaultListClass);
        $itemClass    = $template->getAttribute(IContentList::ITEM_CLASS, $this->defaultItemClass);
        $labelClass   = $template->getAttribute(IContentList::LABEL_CLASS, $this->defaultLabelClass);
        $contentClass = $template->getAttribute(IContentList::CONTENT_CLASS, $this->defaultContentClass);
        $imageClass   = $template->getAttribute(IContentList::IMAGE_CLASS, $this->defaultImageClass);

        return [
            IContentList::LIST_CLASS    => $listClass,
            IContentList::ITEM_CLASS    => $itemClass,
            IContentList::LABEL_CLASS   => $labelClass,
            IContentList::CONTENT_CLASS => $contentClass,
            IContentList::IMAGE_CLASS   => $imageClass,
        ];
    }

    /**
     * @param ParsedTemplate|null $template
     *
     * @return array
     */
    protected function getOptions(?ParsedTemplate $template = null): array
    {
        if (!$template) {
            return [
                IContentList::WITH_LABEL_OPTION  => (bool)$this->defaultWithLabel,
                IContentList::WITH_IMAGES_OPTION => (bool)$this->defaultWithImage,
            ];
        }

        $listClass = $template->getAttribute(IContentList::WITH_LABEL_OPTION, $this->defaultWithLabel);
        $itemClass = $template->getAttribute(IContentList::WITH_IMAGES_OPTION, $this->defaultWithImage);

        return [
            IContentList::WITH_LABEL_OPTION  => (bool)$listClass,
            IContentList::WITH_IMAGES_OPTION => (bool)$itemClass,
        ];
    }
}
