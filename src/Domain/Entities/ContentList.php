<?php

declare(strict_types=1);

namespace AbterPhp\Website\Domain\Entities;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Helper\DateHelper;
use DateTime;

/**
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 */
class ContentList implements IStringerEntity
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $typeId;

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
    protected $withHtml;

    /** @var ContentListItem[]|null */
    protected $items;

    /** @var DateTime|null */
    protected $deletedAt;

    /**
     * ContentList constructor.
     *
     * @param string                 $id
     * @param string                 $typeId
     * @param string                 $name
     * @param string                 $identifier
     * @param string                 $classes
     * @param bool                   $protected
     * @param bool                   $withImage
     * @param bool                   $withLinks
     * @param bool                   $withHtml
     * @param ContentListItem[]|null $items
     * @param DateTime|null          $deletedAt
     */
    public function __construct(
        string $id,
        string $typeId,
        string $name,
        string $identifier,
        string $classes,
        bool $protected,
        bool $withImage,
        bool $withLinks,
        bool $withHtml,
        array $items = null,
        ?DateTime $deletedAt = null
    ) {
        $this->id         = $id;
        $this->typeId     = $typeId;
        $this->name       = $name;
        $this->identifier = $identifier;
        $this->classes    = $classes;
        $this->protected  = $protected;
        $this->withImage  = $withImage;
        $this->withLinks  = $withLinks;
        $this->withHtml   = $withHtml;
        $this->items      = $items;
        $this->deletedAt  = $deletedAt;
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
    public function getTypeId(): string
    {
        return $this->typeId;
    }

    /**
     * @param string $typeId
     *
     * @return $this
     */
    public function setTypeId(string $typeId): ContentList
    {
        $this->typeId = $typeId;

        return $this;
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
     * @return ContentListItem[]|null
     */
    public function getItems(): ?array
    {
        return $this->items;
    }

    /**
     * @param ContentListItem[]|null $items
     *
     * @return $this
     */
    public function setItems(?array $items = null): ContentList
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }

    /**
     * @param DateTime|null $deletedAt
     *
     * @return $this
     */
    public function setDeletedAt(?DateTime $deletedAt): ContentList
    {
        $this->deletedAt = $deletedAt;

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
     * @return string
     */
    public function toJSON(): string
    {
        $data = [
            'id'         => $this->getId(),
            'name'       => $this->getName(),
            'identifier' => $this->getIdentifier(),
            'protected'  => $this->isProtected(),
            'with_image' => $this->isWithImage(),
            'with_links' => $this->isWithLinks(),
            'with_html'  => $this->isWithHtml(),
        ];

        if ($this->items !== null) {
            $items = [];
            foreach ($this->items as $item) {
                $items[] = json_decode($item->toJSON(), true);
            }
            $data['items'] = $items;
        }

        if ($this->getDeletedAt()) {
            $data['deleted_at'] = DateHelper::formatDateTime($this->getDeletedAt());
        }

        return json_encode($data);
    }
}
