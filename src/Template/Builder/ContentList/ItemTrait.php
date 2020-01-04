<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Builder\ContentList;

use AbterPhp\Website\Domain\Entities\ContentList as Entity;
use AbterPhp\Website\Domain\Entities\ContentListItem as Item;

trait ItemTrait
{
    abstract public function getIdentifier(): string;

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
        $image = sprintf('<img src="%s" alt="%s">', $item->getImgSrc(), $item->getImgAlt());

        if ($list->isWithLinks()) {
            $name  = sprintf('<a href="%s">%s</a>', $item->getNameHref(), $name);
            $body  = sprintf('<a href="%s">%s</a>', $item->getBodyHref(), $body);
            $image = sprintf('<a href="%s">%s</a>', $item->getImgHref(), $image);
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
     * @param array $parts
     * @param int[] $order
     *
     * @return string
     */
    protected function joinItem(array $parts, array $order): string
    {
        $ordered = [];
        foreach ($order as $i) {
            if (empty($parts[$i])) {
                continue;
            }

            $ordered[] = $parts[$i];
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
            $joined      = $this->joinItem($parts, [0, 1, 2]);
            $htmlParts[] = sprintf("<$tag>%s</$tag>\n", $joined);
        }

        return implode('', $htmlParts);
    }

    /**
     * @SuppressWarnings(PHPMD.PhanUndeclaredConstant)
     *
     * @param Entity $list
     * @param string $listTag
     * @param string $itemTag
     *
     * @return string
     */
    protected function buildItems(Entity $list, string $listTag, string $itemTag): string
    {
        $content = $this->joinItems($list, $itemTag);

        $classes = array_merge(
            [$this->getIdentifier(), $list->getType()->getName()],
            explode(' ', $list->getClasses())
        );

        $html = sprintf(
            '<%s id="%s" class="%s">%s</%s>' . PHP_EOL,
            $listTag,
            $list->getIdentifier(),
            implode(' ', $classes),
            $content,
            $listTag
        );

        return $html;
    }
}
