<?php

declare(strict_types=1);

namespace AbterPhp\Website\Domain\Entities;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Website\Domain\Entities\Page\Assets;
use AbterPhp\Website\Domain\Entities\Page\Meta;

/**
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 */
class Page implements IStringerEntity
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $identifier;

    /** @var string */
    protected $title;

    /** @var string */
    protected $lead;

    /** @var string */
    protected $body;

    /** @var bool */
    protected $markedAsDraft;

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

    /** @var string|null */
    protected $renderedBody;

    /**
     * Page constructor.
     *
     * @param string            $id
     * @param string            $identifier
     * @param string            $title
     * @param string            $lead
     * @param string            $body
     * @param PageCategory|null $category
     * @param string            $layout
     * @param string|null       $layoutId
     * @param Meta|null         $meta
     * @param Assets|null       $assets
     * @param string|null       $renderedBody
     */
    public function __construct(
        string $id,
        string $identifier,
        string $title,
        string $lead,
        string $body,
        bool $isDraft,
        ?PageCategory $category = null,
        string $layout = '',
        ?string $layoutId = null,
        ?Meta $meta = null,
        ?Assets $assets = null,
        ?string $renderedBody = null
    ) {
        $this->id            = $id;
        $this->identifier    = $identifier;
        $this->title         = $title;
        $this->lead          = $lead;
        $this->body          = $body;
        $this->markedAsDraft = $isDraft;
        $this->category      = $category;
        $this->layout        = $layout;
        $this->layoutId      = $layoutId ? $layoutId : null;
        $this->meta          = $meta ?: new Meta('', '', '', '', '', '', '', '');
        $this->assets        = $assets;
        $this->renderedBody  = $renderedBody;
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
    public function getLead(): string
    {
        return $this->lead;
    }

    /**
     * @param string $lead
     */
    public function setLead(string $lead): Page
    {
        $this->lead = $lead;

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
     * @return bool
     */
    public function isDraft(): bool
    {
        return $this->markedAsDraft;
    }

    /**
     * @param string $isDraft
     *
     * @return $this
     */
    public function setIsDraft(bool $isDraft): Page
    {
        $this->markedAsDraft = $isDraft;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasRenderedBody(): bool
    {
        return !($this->renderedBody === null);
    }

    /**
     * @return string|null
     */
    public function getRenderedBody(): ?string
    {
        return $this->renderedBody;
    }

    /**
     * @param string|null $renderedBody
     *
     * @return $this
     */
    public function setRenderedBody(?string $renderedBody): Page
    {
        $this->renderedBody = $renderedBody;

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
     * @param string|null $layoutId
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
     * @param Assets|null $assets
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

        $meta = [
            'description'    => $meta->getDescription(),
            'robots'         => $meta->getRobots(),
            'author'         => $meta->getAuthor(),
            'copyright'      => $meta->getCopyright(),
            'keywords'       => $meta->getKeywords(),
            'og_title'       => $meta->getOGTitle(),
            'og_image'       => $meta->getOGImage(),
            'og_description' => $meta->getOGDescription(),
        ];

        $data = [
            'id'         => $this->getId(),
            'identifier' => $this->getIdentifier(),
            'title'      => $this->getTitle(),
            'lead'       => $this->getLead(),
            'body'       => $this->getBody(),
            'is_draft'   => $this->isDraft(),
            'category'   => $category,
            'layout'     => $this->getLayout(),
            'layout_id'  => $this->getLayoutId(),
            'meta'       => $meta,
            'assets'     => $assetsData,
        ];

        if ($this->hasRenderedBody()) {
            $data['rendered'] = $this->getRenderedBody();
        }

        return json_encode($data);
    }
}
