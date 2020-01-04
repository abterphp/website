<?php

declare(strict_types=1);

namespace AbterPhp\Website\Domain\Entities;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;

/**
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 */
class ContentListItem implements IStringerEntity
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $listId;

    /** @var string */
    protected $name;

    /** @var string */
    protected $nameHref;

    /** @var string */
    protected $body;

    /** @var string */
    protected $bodyHref;

    /** @var string */
    protected $imgSrc;

    /** @var string */
    protected $imgHref;

    /** @var string */
    protected $imgAlt;

    /** @var bool */
    protected $deleted;

    /**
     * ContentListItem constructor.
     *
     * @param string $id
     * @param string $listId
     * @param string $name
     * @param string $nameHref
     * @param string $body
     * @param string $bodyHref
     * @param string $imgSrc
     * @param string $imgHref
     * @param string $imgAlt
     * @param bool   $isDeleted
     */
    public function __construct(
        string $id,
        string $listId,
        string $name,
        string $nameHref,
        string $body,
        string $bodyHref,
        string $imgSrc,
        string $imgHref,
        string $imgAlt,
        bool $isDeleted = false
    ) {
        $this->id       = $id;
        $this->listId   = $listId;
        $this->name     = $name;
        $this->nameHref = $nameHref;
        $this->body     = $body;
        $this->bodyHref = $bodyHref;
        $this->imgSrc   = $imgSrc;
        $this->imgHref  = $imgHref;
        $this->imgAlt   = $imgAlt;
        $this->deleted  = $isDeleted;
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
    public function getListId(): string
    {
        return $this->listId;
    }

    /**
     * @param string $listId
     *
     * @return $this
     */
    public function setListId(string $listId): ContentListItem
    {
        $this->listId = $listId;

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
    public function setName(string $name): ContentListItem
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getNameHref(): string
    {
        return $this->nameHref;
    }

    /**
     * @param string $nameHref
     *
     * @return $this
     */
    public function setNameHref(string $nameHref): ContentListItem
    {
        $this->nameHref = $nameHref;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return $this
     */
    public function setBody(string $body): ContentListItem
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return string
     */
    public function getBodyHref(): string
    {
        return $this->bodyHref;
    }

    /**
     * @param string $bodyHref
     *
     * @return $this
     */
    public function setBodyHref(string $bodyHref): ContentListItem
    {
        $this->bodyHref = $bodyHref;

        return $this;
    }

    /**
     * @return string
     */
    public function getImgSrc(): string
    {
        return $this->imgSrc;
    }

    /**
     * @param string $imgSrc
     *
     * @return $this
     */
    public function setImgSrc(string $imgSrc): ContentListItem
    {
        $this->imgSrc = $imgSrc;

        return $this;
    }

    /**
     * @return string
     */
    public function getImgHref(): string
    {
        return $this->imgHref;
    }

    /**
     * @param string $imgHref
     *
     * @return $this
     */
    public function setImgHref(string $imgHref): ContentListItem
    {
        $this->imgHref = $imgHref;

        return $this;
    }

    /**
     * @return string
     */
    public function getImgAlt(): string
    {
        return $this->imgAlt;
    }

    /**
     * @param string $imgAlt
     *
     * @return $this
     */
    public function setImgAlt(string $imgAlt): ContentListItem
    {
        $this->imgAlt = $imgAlt;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     */
    public function setDeleted(bool $deleted): ContentListItem
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @return array|null
     */
    public function toData(): ?array
    {
        if ($this->deleted) {
            return null;
        }

        $data = [
            'id'        => $this->getId(),
            'list_id'   => $this->getListId(),
            'name'      => $this->getName(),
            'name_href' => $this->getNameHref(),
            'body'      => $this->getBody(),
            'body_href' => $this->getBodyHref(),
            'img_src'   => $this->getImgSrc(),
            'img_href'  => $this->getImgHref(),
            'img_alt'   => $this->getImgAlt(),
        ];

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
