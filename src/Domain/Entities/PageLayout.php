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
    protected $name;

    /** @var string */
    protected $identifier;

    /** @var string */
    protected $classes;

    /** @var string */
    protected $body;

    /** @var Assets|null */
    protected $assets;

    /**
     * PageLayout constructor.
     *
     * @param string      $id
     * @param string      $name
     * @param string      $identifier
     * @param string      $classes
     * @param string      $body
     * @param Assets|null $assets
     */
    public function __construct(
        string $id,
        string $name,
        string $identifier,
        string $classes,
        string $body,
        ?Assets $assets
    ) {
        $this->id         = $id;
        $this->name       = $name;
        $this->identifier = $identifier;
        $this->classes    = $classes;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): PageLayout
    {
        $this->name = $name;

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
    public function setIdentifier(string $identifier): PageLayout
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
    public function setClasses(string $classes): PageLayout
    {
        $this->classes = $classes;

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

    /**
     * @return array|null
     */
    public function toData(): ?array
    {
        $assetsData = null;
        if ($this->getAssets()) {
            $assets = $this->getAssets();

            $assetsData = [
                'key'       => $assets->getKey(),
                'header'    => $assets->getHeader(),
                'footer'    => $assets->getFooter(),
                'css_files' => $assets->getCssFiles(),
                'js_files'  => $assets->getJsFiles(),
            ];
        }

        return [
            'id'         => $this->getId(),
            'identifier' => $this->getIdentifier(),
            'classes'    => $this->getClasses(),
            'body'       => $this->getBody(),
            'assets'     => $assetsData,
        ];
    }

    /**
     * @return string
     */
    public function toJSON(): string
    {
        return json_encode($this->toData());
    }
}
