<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Builder\ContentList;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Html\Helper\StringHelper;
use AbterPhp\Website\Domain\Entities\ContentList as Entity;
use AbterPhp\Website\Domain\Entities\ContentListItem as Item;

trait ItemTrait
{
    /**
     * @return string
     */
    abstract public function getIdentifier(): string;

    /**
     * @return string[]
     */
    abstract public function getPartClassesByOrder(): array;

    /**
     * @return string[]
     */
    abstract public function wrapperTags(): array;

    /**
     * @param Item   $item
     * @param Entity $list
     *
     * @return array
     */
    protected function buildParts(Item $item, Entity $list): array
    {
        $name  = $item->getName();
        $body  = $item->getBody();
        $image = StringHelper::createTag(
            Html5::TAG_IMG,
            [Html5::ATTR_SRC => $item->getImgSrc(), Html5::ATTR_ALT => $item->getImgAlt()]
        );

        if ($list->isWithLinks()) {
            $name  = StringHelper::wrapInTag($name, Html5::TAG_A, [Html5::ATTR_HREF => $item->getNameHref()]);
            $body  = StringHelper::wrapInTag($body, Html5::TAG_A, [Html5::ATTR_HREF => $item->getBodyHref()]);
            $image  = StringHelper::wrapInTag($image, Html5::TAG_A, [Html5::ATTR_HREF => $item->getImgHref()]);
        }

        if (!$list->isWithBody()) {
            $body = null;
        }

        if (!$list->isWithImage()) {
            $image = null;
        }

        return [$name, $body, $image];
    }

    /**
     * @param string[] $parts
     *
     * @return string
     */
    protected function joinItem(array $parts): string
    {
        $tags = $this->wrapperTags();

        $ordered = [];
        foreach ($this->getPartClassesByOrder() as $i => $class) {
            if (empty($parts[$i]) || empty($tags[$i])) {
                continue;
            }

            $ordered[] = StringHelper::wrapInTag($parts[$i], $tags[$i], [Html5::ATTR_CLASS => $class]);
        }

        return implode('', $ordered);
    }

    /**
     * @param Entity $list
     * @param string $tag
     *
     * @return string
     */
    protected function joinItems(Entity $list, string $tag): string
    {
        if ($list->getItems() === null) {
            return '';
        }

        $htmlParts = [];
        foreach ($list->getItems() as $item) {
            $parts       = $this->buildParts($item, $list);
            $joined      = $this->joinItem($parts);
            $htmlParts[] = sprintf("<$tag>%s</$tag>\n", $joined);
        }

        return implode('', $htmlParts);
    }

    /**
     * @param Entity $list
     * @param string $listTag
     * @param string $itemTag
     *
     * @return string
     */
    protected function buildItems(Entity $list, string $listTag, string $itemTag): string
    {
        $content = $this->joinItems($list, $itemTag);

        $classes = [$this->getIdentifier(), $list->getType()->getName()];
        if ($list->getClasses()) {
            $classes = array_merge($classes, explode(' ', $list->getClasses()));
        }

        $html = StringHelper::wrapInTag(
            $content,
            $listTag,
            [Html5::ATTR_ID => $list->getIdentifier(), Html5::ATTR_CLASS => implode(' ', $classes)]
        );

        return $html;
    }
}
