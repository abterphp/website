<?php

declare(strict_types=1);

namespace AbterPhp\Website\Domain\Entities;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Helper\DateHelper;
use AbterPhp\Website\Domain\Entities\ContentListItem as Item;
use AbterPhp\Website\Domain\Entities\ContentListType as Type;
use DateTime;

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
    protected $withImage;

    /** @var bool */
    protected $withLinks;

    /** @var bool */
    protected $withBody;

    /** @var bool */
    protected $withHtml;

    /** @var Type */
    protected $type;

    /** @var Item[]|null */
    protected $items;

    /**
     * ContentList constructor.
     *
     * @param string        $id
     * @param string        $name
     * @param string        $identifier
     * @param string        $classes
     * @param bool          $protected
     * @param bool          $withLinks
     * @param bool          $withImage
     * @param bool          $withBody
     * @param bool          $withHtml
     * @param Type          $type
     * @param Item[]|null   $items
     */
    public function __construct(
        string $id,
        string $name,
        string $identifier,
        string $classes,
        bool $protected,
        bool $withLinks,
        bool $withImage,
        bool $withBody,
        bool $withHtml,
        ?Type $type = null,
        array $items = null
    ) {
        $this->id         = $id;
        $this->name       = $name;
        $this->identifier = $identifier;
        $this->classes    = $classes;
        $this->protected  = $protected;
        $this->withLinks  = $withLinks;
        $this->withImage  = $withImage;
        $this->withBody   = $withBody;
        $this->withHtml   = $withHtml;
        $this->type       = $type ?: new Type('', '', '');
        $this->items      = $items;
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
    public function setIdentifier(string $identifier): ContentList
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
    public function setClasses(string $classes): ContentList
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
    public function setName(string $name): ContentList
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
    public function setProtected(bool $protected): ContentList
    {
        $this->protected = $protected;

        return $this;
    }

    /**
     * @return bool
     */
    public function isWithImage(): bool
    {
        return $this->withImage;
    }

    /**
     * @param bool $withImage
     *
     * @return $this
     */
    public function setWithImage(bool $withImage): ContentList
    {
        $this->withImage = $withImage;

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
    public function setWithLinks(bool $withLinks): ContentList
    {
        $this->withLinks = $withLinks;

        return $this;
    }

    /**
     * @return bool
     */
    public function isWithBody(): bool
    {
        return $this->withBody;
    }

    /**
     * @param bool $withBody
     *
     * @return $this
     */
    public function setWithBody(bool $withBody): ContentList
    {
        $this->withBody = $withBody;

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
    public function setWithHtml(bool $withHtml): ContentList
    {
        $this->withHtml = $withHtml;

        return $this;
    }

    /**
     * @return Type
     */
    public function getType(): Type
    {
        return $this->type;
    }

    /**
     * @param Type $type
     *
     * @return $this
     */
    public function setType(Type $type): ContentList
    {
        $this->type = $type;

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
    public function setItems(?array $items = null): ContentList
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @param Item $item
     *
     * @return ContentList
     */
    public function addItem(Item $item): ContentList
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
     * @return array
     */
    public function getData(): array
    {
        $data = [
            'id'         => $this->getId(),
            'name'       => $this->getName(),
            'identifier' => $this->getIdentifier(),
            'classes'    => $this->getClasses(),
            'protected'  => $this->isProtected(),
            'with_links' => $this->isWithLinks(),
            'with_image' => $this->isWithImage(),
            'with_body'  => $this->withBody(),
            'with_html'  => $this->isWithHtml(),
        ];

        if ($this->items !== null) {
            $items = [];
            foreach ($this->items as $item) {
                $items[] = $item->getData();
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
        return json_encode($this->getData());
    }
}
