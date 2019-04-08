<?php

declare(strict_types=1);

namespace AbterPhp\Website\Domain\Entities;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Website\Domain\Entities\PageLayout\Assets;

class PageLayout implements IStringerEntity
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $identifier;

    /** @var string */
    protected $body;

    /** @var Assets|null */
    protected $assets;

    /**
     * Page constructor.
     *
     * @param string      $id
     * @param string      $identifier
     * @param string      $body
     * @param Assets|null $assets
     */
    public function __construct(string $id, string $identifier, string $body, ?Assets $assets)
    {
        $this->id         = $id;
        $this->identifier = $identifier;
        $this->body       = $body;
        $this->assets     = $assets;
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
    public function setIdentifier(string $identifier): PageLayout
    {
        $this->identifier = $identifier;

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
    public function setBody(string $body): PageLayout
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return Assets|null
     */
    public function getAssets(): ?Assets
    {
        return $this->assets;
    }

    /**
     * @param Assets|null $assets
     *
     * @return $this
     */
    public function setAssets(?Assets $assets): PageLayout
    {
        $this->assets = $assets;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getIdentifier();
    }
}
