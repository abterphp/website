<?php

namespace AbterPhp\Website\Template\Builder\ContentList;

use AbterPhp\Website\Domain\Entities\ContentList as Entity;
use AbterPhp\Website\Domain\Entities\ContentListItem as Item;
use AbterPhp\Website\Domain\Entities\ContentListType as Type;
use PHPUnit\Framework\TestCase;

abstract class ContentListTest extends TestCase
{
    /**
     * @param string $typeName
     * @param string $identifier
     * @param string ...$itemIds
     *
     * @return Entity
     */
    protected function createEntity(string $typeName, string $identifier, string ...$itemIds): Entity
    {

        $type   = new Type("type-$identifier", $typeName, '');
        $entity = new Entity("list-$identifier", '', $identifier, '', false, false, false, false, false, $type);
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
        $name     = "Foo $postfix";
        $nameHref = "/foo-$postfix";
        $body     = "Bar $postfix";
        $bodyHref = "/bar-$postfix";
        $imgSrc   = "/baz0-$postfix";
        $imgHref  = "/baz1-$postfix";
        $imgAlt   = "Baz $postfix";

        return new Item($id, $id, $name, $nameHref, $body, $bodyHref, $imgSrc, $imgHref, $imgAlt);
    }
}
