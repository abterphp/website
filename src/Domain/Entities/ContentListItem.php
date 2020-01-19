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
    protected $label;

    /** @var string */
    protected $labelHref;

    /** @var string */
    protected $content;

    /** @var string */
    protected $contentHref;

    /** @var string */
    protected $imgSrc;

    /** @var string */
    protected $imgAlt;

    /** @var string */
    protected $imgHref;

    /** @var string */
    protected $classes;

    /** @var bool */
    protected $deleted;

    /**
     * ContentListItem constructor.
     *
     * @param string $id
     * @param string $listId
     * @param string $label
     * @param string $labelHref
     * @param string $content
     * @param string $contentHref
     * @param string $imgSrc
     * @param string $imgAlt
     * @param string $imgHref
     * @param string $classes
     * @param bool   $isDeleted
     */
    public function __construct(
        string $id,
        string $listId,
        string $label,
        string $labelHref,
        string $content,
        string $contentHref,
        string $imgSrc,
        string $imgAlt,
        string $imgHref,
        string $classes,
        bool $isDeleted = false
    ) {
        $this->id          = $id;
        $this->listId      = $listId;
        $this->label       = $label;
        $this->labelHref   = $labelHref;
        $this->content     = $content;
        $this->contentHref = $contentHref;
        $this->imgSrc      = $imgSrc;
        $this->imgAlt      = $imgAlt;
        $this->imgHref     = $imgHref;
        $this->classes     = $classes;
        $this->deleted     = $isDeleted;
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
    public function setListId(string $listId): self
    {
        $this->listId = $listId;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabelHref(): string
    {
        return $this->labelHref;
    }

    /**
     * @param string $labelHref
     *
     * @return $this
     */
    public function setLabelHref(string $labelHref): self
    {
        $this->labelHref = $labelHref;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getContentHref(): string
    {
        return $this->contentHref;
    }

    /**
     * @param string $contentHref
     *
     * @return $this
     */
    public function setContentHref(string $contentHref): self
    {
        $this->contentHref = $contentHref;

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
    public function setImgSrc(string $imgSrc): self
    {
        $this->imgSrc = $imgSrc;

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
    public function setImgAlt(string $imgAlt): self
    {
        $this->imgAlt = $imgAlt;

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
    public function setImgHref(string $imgHref): self
    {
        $this->imgHref = $imgHref;

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
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     */
    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getLabel();
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
            'id'           => $this->getId(),
            'list_id'      => $this->getListId(),
            'label'        => $this->getLabel(),
            'label_href'   => $this->getLabelHref(),
            'content'      => $this->getContent(),
            'content_href' => $this->getContentHref(),
            'img_src'      => $this->getImgSrc(),
            'img_href'     => $this->getImgHref(),
            'img_alt'      => $this->getImgAlt(),
            'classes'      => $this->getClasses(),
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
