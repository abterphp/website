<?php

declare(strict_types=1);

namespace AbterPhp\Website\Domain\Entities;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Website\Domain\Entities\ContentListItem as Item;

/**
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 */
class ContentList implements IStringerEntity
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $identifier;

    /** @var string */
    protected $classes;

    /** @var bool */
    protected $protected;

    /** @var bool */
    protected $withLinks;

    /** @var bool */
    protected $withLabelLinks;

    /** @var bool */
    protected $withHtml;

    /** @var bool */
    protected $withImages;

    /** @var bool */
    protected $withClasses;

    /** @var Item[]|null */
    protected $items;

    /**
     * ContentList constructor.
     *
     * @param string      $id
     * @param string      $name
     * @param string      $identifier
     * @param string      $classes
     * @param bool        $protected
     * @param bool        $withLinks
     * @param bool        $withLabelLinks
     * @param bool        $withHtml
     * @param bool        $withImages
     * @param bool        $withClasses
     * @param Item[]|null $items
     */
    public function __construct(
        string $id,
        string $name,
        string $identifier,
        string $classes,
        bool $protected,
        bool $withLinks,
        bool $withLabelLinks,
        bool $withHtml,
        bool $withImages,
        bool $withClasses,
        array $items = null
    ) {
        $this->id             = $id;
        $this->name           = $name;
        $this->identifier     = $identifier;
        $this->classes        = $classes;
        $this->protected      = $protected;
        $this->withLinks      = $withLinks;
        $this->withLabelLinks = $withLabelLinks;
        $this->withHtml       = $withHtml;
        $this->withImages     = $withImages;
        $this->withClasses    = $withClasses;
        $this->items          = $items;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     *
     * @return $this
     */
    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return string
     */
    public function getClasses(): string
    {
        return $this->classes;
    }

    /**
     * @param string $classes
     *
     * @return $this
     */
    public function setClasses(string $classes): self
    {
        $this->classes = $classes;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool
     */
    public function isProtected(): bool
    {
        return $this->protected;
    }

    /**
     * @param bool $protected
     *
     * @return $this
     */
    public function setProtected(bool $protected): self
    {
        $this->protected = $protected;

        return $this;
    }

    /**
     * @return bool
     */
    public function isWithLinks(): bool
    {
        return $this->withLinks;
    }

    /**
     * @param bool $withLinks
     *
     * @return $this
     */
    public function setWithLinks(bool $withLinks): self
    {
        $this->withLinks = $withLinks;

        return $this;
    }

    /**
     * @return bool
     */
    public function isWithLabelLinks(): bool
    {
        return $this->withLabelLinks;
    }

    /**
     * @param bool $withLabelLinks
     *
     * @return $this
     */
    public function setWithLabelLinks(bool $withLabelLinks): self
    {
        $this->withLabelLinks = $withLabelLinks;

        return $this;
    }

    /**
     * @return bool
     */
    public function isWithHtml(): bool
    {
        return $this->withHtml;
    }

    /**
     * @param bool $withHtml
     *
     * @return $this
     */
    public function setWithHtml(bool $withHtml): self
    {
        $this->withHtml = $withHtml;

        return $this;
    }

    /**
     * @return bool
     */
    public function isWithImages(): bool
    {
        return $this->withImages;
    }

    /**
     * @param bool $withImages
     *
     * @return $this
     */
    public function setWithImages(bool $withImages): self
    {
        $this->withImages = $withImages;

        return $this;
    }

    /**
     * @return bool
     */
    public function isWithClasses(): bool
    {
        return $this->withClasses;
    }

    /**
     * @param bool $withClasses
     *
     * @return $this
     */
    public function setWithClasses(bool $withClasses): self
    {
        $this->withClasses = $withClasses;

        return $this;
    }

    /**
     * @return Item[]|null
     */
    public function getItems(): ?array
    {
        return $this->items;
    }

    /**
     * @param Item[]|null $items
     *
     * @return $this
     */
    public function setItems(?array $items = null): self
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @param Item $item
     *
     * @return ContentList
     */
    public function addItem(Item $item): self
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getIdentifier();
    }

    /**
     * @return array|null
     */
    public function toData(): ?array
    {
        $data = [
            'id'               => $this->getId(),
            'name'             => $this->getName(),
            'identifier'       => $this->getIdentifier(),
            'classes'          => $this->getClasses(),
            'protected'        => $this->isProtected(),
            'with_links'       => $this->isWithLinks(),
            'with_label_links' => $this->isWithLabelLinks(),
            'with_html'        => $this->isWithHtml(),
            'with_images'      => $this->isWithImages(),
            'with_classes'     => $this->isWithClasses(),
        ];

        if ($this->items !== null) {
            $items = [];
            foreach ($this->items as $item) {
                $items[] = $item->toData();
            }
            $data['items'] = $items;
        }

        return $data;
    }

    /**
     * @return string
     */
    public function toJSON(): string
    {
        return json_encode($this->toData());
    }
}
