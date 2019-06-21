<?php

declare(strict_types=1);

namespace AbterPhp\Website\Domain\Entities;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Website\Domain\Entities\Page\Assets;
use AbterPhp\Website\Domain\Entities\Page\Meta;

class Page implements IStringerEntity
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $identifier;

    /** @var string */
    protected $title;

    /** @var string */
    protected $body;

    /** @var PageCategory|null */
    protected $category;

    /** @var string */
    protected $layout;

    /** @var string|null */
    protected $layoutId;

    /** @var Meta */
    protected $meta;

    /** @var Assets|null */
    protected $assets;

    /**
     * Page constructor.
     *
     * @param string            $id
     * @param string            $identifier
     * @param string            $title
     * @param string            $body
     * @param PageCategory|null $category
     * @param string            $layout
     * @param string|null       $layoutId
     * @param Meta|null         $meta
     * @param Assets|null       $assets
     */
    public function __construct(
        string $id,
        string $identifier,
        string $title,
        string $body,
        ?PageCategory $category = null,
        string $layout = '',
        ?string $layoutId = null,
        ?Meta $meta = null,
        ?Assets $assets = null
    ) {
        $this->id         = $id;
        $this->identifier = $identifier;
        $this->title      = $title;
        $this->body       = $body;
        $this->category   = $category;
        $this->layout     = $layout;
        $this->layoutId   = $layoutId ? $layoutId : null;
        $this->meta       = $meta ?: new Meta('', '', '', '', '', '', '', '');
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
    public function setIdentifier(string $identifier): Page
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title): Page
    {
        $this->title = $title;

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
    public function setBody(string $body): Page
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return PageCategory|null
     */
    public function getCategory(): ?PageCategory
    {
        return $this->category;
    }

    /**
     * @param PageCategory|null $category
     *
     * @return $this
     */
    public function setCategory(?PageCategory $category): Page
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string
     */
    public function getLayout(): string
    {
        return $this->layout;
    }

    /**
     * @param string $layout
     *
     * @return $this
     */
    public function setLayout(string $layout): Page
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLayoutId(): ?string
    {
        return $this->layoutId;
    }

    /**
     * @param string|null $layout
     *
     * @return $this
     */
    public function setLayoutId(?string $layoutId): Page
    {
        if ($layoutId === '') {
            $layoutId = null;
        }

        $this->layoutId = $layoutId;

        return $this;
    }

    /**
     * @return Meta
     */
    public function getMeta(): Meta
    {
        return $this->meta;
    }

    /**
     * @param Meta $meta
     *
     * @return $this
     */
    public function setMeta(Meta $meta): Page
    {
        $this->meta = $meta;

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
     * @param Assets|null $header
     *
     * @return $this
     */
    public function setAssets(?Assets $assets): Page
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
     * @return string
     */
    public function toJSON(): string
    {
        $meta   = $this->getMeta();
        $assets = $this->getAssets();

        $category = null;
        if ($this->getCategory()) {
            $category = [
                'id' => $this->getCategory()->getId(),
            ];
        }

        $assetsData = null;
        if ($assets) {
            $assetsData = [
                'key'       => $assets->getKey(),
                'header'    => $assets->getHeader(),
                'footer'    => $assets->getFooter(),
                'css_files' => $assets->getCssFiles(),
                'js_files'  => $assets->getJsFiles(),
            ];
        }

        return json_encode(
            [
                'id'         => $this->getId(),
                'identifier' => $this->getIdentifier(),
                'title'      => $this->getTitle(),
                'body'       => $this->getBody(),
                'category'   => $category,
                'layout'     => $this->getLayout(),
                'layout_id'  => $this->getLayoutId(),
                'meta'       => [
                    'description'    => $meta->getDescription(),
                    'robots'         => $meta->getRobots(),
                    'author'         => $meta->getAuthor(),
                    'copyright'      => $meta->getCopyright(),
                    'keywords'       => $meta->getKeywords(),
                    'og_title'       => $meta->getOGTitle(),
                    'og_image'       => $meta->getOGImage(),
                    'og_description' => $meta->getOGDescription(),
                ],
                'assets'     => $assetsData,
            ]
        );
    }
}
