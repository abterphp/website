<?php

declare(strict_types=1);

namespace AbterPhp\Website\Domain\Entities;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Helper\DateHelper;
use DateTime;

class ContentListType implements IStringerEntity
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $label;

    /** @var DateTime|null */
    protected $deletedAt;

    /**
     * ContentListType constructor.
     *
     * @param string        $id
     * @param string        $name
     * @param string        $label
     * @param DateTime|null $deletedAt
     */
    public function __construct(string $id, string $name, string $label, ?DateTime $deletedAt = null)
    {
        $this->id        = $id;
        $this->name      = $name;
        $this->label     = $label;
        $this->deletedAt = $deletedAt;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): ContentListType
    {
        $this->name = $name;

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
    public function setLabel(string $label): ContentListType
    {
        $this->label = $label;

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
    public function setDeletedAt(?DateTime $deletedAt): ContentListType
    {
        $this->deletedAt = $deletedAt;

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
     * @return string
     */
    public function toJSON(): string
    {
        $data = [
            'id'    => $this->getId(),
            'name'  => $this->getName(),
            'label' => $this->getLabel(),
        ];

        if ($this->getDeletedAt()) {
            $data['deleted_at'] = DateHelper::formatDateTime($this->getDeletedAt());
        }

        return json_encode($data);
    }
}
