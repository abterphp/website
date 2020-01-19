<?php

namespace AbterPhp\Website\Template\Builder\ContentList;

use AbterPhp\Website\Domain\Entities\ContentList as Entity;
use AbterPhp\Website\Domain\Entities\ContentListItem as Item;
use PHPUnit\Framework\TestCase;

abstract class ContentListTest extends TestCase
{
    /**
     * @param string $identifier
     * @param string ...$itemIds
     *
     * @return Entity
     */
    protected function createEntity(string $identifier, string ...$itemIds): Entity
    {
        $entity = new Entity("list-$identifier", '', $identifier, '', false, false, false, false, false, false);
        $items  = [];
        foreach ($itemIds as $itemId) {
            $entity->addItem($this->createItem("item-$itemId", $itemId));
        }

        return $entity;
    }

    /**
     * @param string $id
     * @param string $postfix
     *
     * @return Item
     */
    protected function createItem(string $id, string $postfix): Item
    {
        $label       = "Foo $postfix";
        $labelHref   = "/foo-$postfix";
        $content     = "Bar $postfix";
        $contentHref = "/bar-$postfix";
        $imgSrc      = "/baz-$postfix.png";
        $imgAlt      = "Baz $postfix";
        $imgHref     = "/baz-$postfix";
        $classes     = "$postfix";

        return new Item($id, $id, $label, $labelHref, $content, $contentHref, $imgSrc, $imgAlt, $imgHref, $classes);
    }
}
