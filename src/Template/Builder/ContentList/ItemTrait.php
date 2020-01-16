<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Builder\ContentList;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Html\Helper\StringHelper;
use AbterPhp\Framework\Template\IBuilder;
use AbterPhp\Website\Domain\Entities\ContentList as Entity;
use AbterPhp\Website\Domain\Entities\ContentListItem as Item;

trait ItemTrait
{
    /** @var bool */
    protected $withName = true;

    /**
     * @return string
     */
    abstract public function getIdentifier(): string;

    /**
     * @return string[]
     */
    public function getPartClassesByOrder(): array
    {
        return [
            IBuilder::NAME  => 'item-name',
            IBuilder::BODY  => 'item-body',
            IBuilder::IMAGE => 'item-image',
        ];
    }

    /**
     * @return string[]
     */
    public function wrapperTags(): array
    {
        return [
            IBuilder::NAME  => Html5::TAG_SPAN,
            IBuilder::BODY  => Html5::TAG_SPAN,
            IBuilder::IMAGE => Html5::TAG_SPAN,
        ];
    }

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
        $image = null;

        if ($list->isWithImage()) {
            $image = StringHelper::createTag(
                Html5::TAG_IMG,
                [Html5::ATTR_SRC => $item->getImgSrc(), Html5::ATTR_ALT => $item->getImgAlt()]
            );
        }

        if ($list->isWithLinks()) {
            $name  = StringHelper::wrapInTag($name, Html5::TAG_A, [Html5::ATTR_HREF => $item->getNameHref()]);
            $body  = StringHelper::wrapInTag($body, Html5::TAG_A, [Html5::ATTR_HREF => $item->getBodyHref()]);
            if ($image) {
                $image = StringHelper::wrapInTag($image, Html5::TAG_A, [Html5::ATTR_HREF => $item->getImgHref()]);
            }
        }

        if (!$this->withName) {
            $name = null;
        }

        if (!$list->isWithBody()) {
            $body = null;
        }

        return [
            IBuilder::NAME  => $name,
            IBuilder::BODY  => $body,
            IBuilder::IMAGE => $image,
        ];
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
        foreach ($this->getPartClassesByOrder() as $partName => $class) {
            if (empty($parts[$partName])) {
                continue;
            }

            if (empty($tags[$partName])) {
                $ordered[] = $parts[$partName];
                continue;
            }

            $ordered[] = StringHelper::wrapInTag($parts[$partName], $tags[$partName], [Html5::ATTR_CLASS => $class]);
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
            $parts  = $this->buildParts($item, $list);
            $joined = $this->joinItem($parts);
            if ($tag) {
                $htmlParts[] = sprintf("<$tag>%s</$tag>\n", $joined);
            } else {
                $htmlParts[] = sprintf("%s\n", $joined);
            }
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
