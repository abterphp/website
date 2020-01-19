<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Builder\ContentList;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Html\Helper\StringHelper;
use AbterPhp\Framework\Template\Data;
use AbterPhp\Framework\Template\IData;
use AbterPhp\Framework\Template\ParsedTemplate;
use AbterPhp\Website\Domain\Entities\ContentList as Entity;
use AbterPhp\Website\Domain\Entities\ContentListItem as Item;

trait ItemTrait
{
    /**
     * @return string
     */
    abstract public function getIdentifier(): string;

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param Entity              $list
     * @param ParsedTemplate|null $template
     *
     * @return IData
     */
    public function build($list, ?ParsedTemplate $template = null): IData
    {
        $wrapperTags    = $this->getWrapperTags($template);
        $wrapperClasses = $this->getWrapperClasses($template);
        $options        = $this->getOptions($template);

        $content    = $this->getContent($list, $wrapperTags, $wrapperClasses, $options);
        $tag        = $wrapperTags[IContentList::LIST_TAG];
        $classes    = $this->getListClasses($list->getClasses(), $wrapperClasses[IContentList::LIST_CLASS]);
        $attributes = [Html5::ATTR_ID => $list->getIdentifier(), Html5::ATTR_CLASS => $classes];

        $html = StringHelper::wrapInTag($content, $tag, $attributes);

        return new Data(
            $list->getIdentifier(),
            [],
            ['body' => $html]
        );
    }

    /**
     * @param ParsedTemplate|null $template
     *
     * @return array<string,string>
     */
    abstract protected function getWrapperTags(?ParsedTemplate $template = null): array;


    /**
     * @param ParsedTemplate|null $template
     *
     * @return array<string,string>
     */
    abstract protected function getWrapperClasses(?ParsedTemplate $template = null): array;


    /**
     * @param ParsedTemplate|null $template
     *
     * @return array<string,string>
     */
    abstract protected function getOptions(?ParsedTemplate $template = null): array;

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param Entity          $list
     * @param  array<string,string> $tags
     * @param  array<string,string> $classes
     * @param  array<string,string> $options
     *
     * @return string
     */
    protected function getContent(Entity $list, array $tags, array $classes, array $options): string
    {
        if ($list->getItems() === null) {
            return '';
        }

        $htmlParts = [];
        foreach ($list->getItems() as $item) {
            $parts   = $this->buildItemParts($item, $list, $tags, $classes, $options);
            $content = $this->joinItemParts($parts);

            $tag   = $tags[IContentList::ITEM_TAG];
            $class = $classes[IContentList::ITEM_CLASS];

            $htmlParts[] = StringHelper::wrapInTag($content, $tag, [Html5::ATTR_CLASS => $class]);
        }

        return implode(PHP_EOL, $htmlParts);
    }

    /**
     * @param Item            $item
     * @param Entity          $list
     * @param  array<string,string> $tags
     * @param  array<string,string> $classes
     * @param  array<string,string> $options
     *
     * @return array
     */
    protected function buildItemParts(Item $item, Entity $list, array $tags, array $classes, array $options): array
    {
        return [
            IContentList::LABEL   => $this->buildLabel($item, $list, $tags, $classes, $options),
            IContentList::CONTENT => $this->buildContent($item, $list, $tags, $classes, $options),
            IContentList::IMAGE   => $this->buildImage($item, $list, $tags, $classes, $options),
        ];
    }

    /**
     * @param Item            $item
     * @param Entity          $list
     * @param  array<string,string> $tags
     * @param  array<string,string> $classes
     * @param  array<string,string> $options
     *
     * @return string
     */
    protected function buildLabel(Item $item, Entity $list, array $tags, array $classes, array $options): string
    {
        if (!$options[IContentList::WITH_LABEL_OPTION]) {
            return '';
        }

        $label = $item->getLabel();

        if ($list->isWithLinks()) {
            $href = $item->getContentHref();
            if ($list->isWithLabelLinks()) {
                $href = $item->getLabelHref() ?: $item->getContentHref();
            }

            $label = StringHelper::wrapInTag($label, Html5::TAG_A, [Html5::ATTR_HREF => $href]);
        }

        $tag   = $tags[IContentList::LABEL_TAG];
        $class = $classes[IContentList::LABEL_CLASS];
        $label = StringHelper::wrapInTag($label, $tag, [Html5::ATTR_CLASS => $class]);

        return $label;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param Item            $item
     * @param Entity          $list
     * @param  array<string,string> $tags
     * @param  array<string,string> $classes
     * @param  array<string,string> $options
     *
     * @return string
     */
    protected function buildContent(Item $item, Entity $list, array $tags, array $classes, array $options): string
    {
        $content = $item->getContent();

        if ($list->isWithLinks()) {
            $content = StringHelper::wrapInTag($content, Html5::TAG_A, [Html5::ATTR_HREF => $item->getContentHref()]);
        }

        $tag     = $tags[IContentList::CONTENT_TAG];
        $class   = $classes[IContentList::CONTENT_CLASS];
        $content = StringHelper::wrapInTag($content, $tag, [Html5::ATTR_CLASS => $class]);

        return $content;
    }

    /**
     * @param Item            $item
     * @param Entity          $list
     * @param  array<string,string> $tags
     * @param  array<string,string> $classes
     * @param  array<string,string> $options
     *
     * @return string
     */
    protected function buildImage(Item $item, Entity $list, array $tags, array $classes, array $options): string
    {
        if (!$options[IContentList::WITH_IMAGES_OPTION] || !$list->isWithImages()) {
            return '';
        }

        $attr  = [Html5::ATTR_SRC => $item->getImgSrc(), Html5::ATTR_ALT => $item->getImgAlt()];
        $image = StringHelper::createTag(Html5::TAG_IMG, $attr);

        if ($list->isWithLinks()) {
            $href  = $item->getImgHref() ?: $item->getContentHref();
            $image = StringHelper::wrapInTag($image, Html5::TAG_A, [Html5::ATTR_HREF => $href]);
        }

        $tag   = $tags[IContentList::IMAGE_TAG];
        $class = $classes[IContentList::IMAGE_CLASS];
        $image = StringHelper::wrapInTag($image, $tag, [Html5::ATTR_CLASS => $class]);

        return $image;
    }

    /**
     * @param array<string,string> $parts
     *
     * @return string
     */
    protected function joinItemParts(array $parts): string
    {
        $content = '';
        foreach ($parts as $part) {
            if (!$part) {
                continue;
            }

            $content .= $part;
        }

        return $content;
    }

    /**
     * @param string $classes
     * @param string $attributeClasses
     *
     * @return string
     */
    protected function getListClasses(string $classes, string $attributeClasses): string
    {
        $trimmedClasses = [$this->getIdentifier() => $this->getIdentifier()];

        foreach (explode(' ', $classes) as $class) {
            $trimmedClass = trim($class);
            if ($trimmedClass) {
                $trimmedClasses[$trimmedClass] = $trimmedClass;
            }
        }
        foreach (explode(' ', $attributeClasses) as $class) {
            $trimmedClass = trim($class);
            if ($trimmedClass) {
                $trimmedClasses[$trimmedClass] = $trimmedClass;
            }
        }

        return implode(' ', $trimmedClasses);
    }
}
