<?php

declare(strict_types=1);

namespace AbterPhp\Website\Domain\Entities\Page;

use AbterPhp\Website\Domain\Entities\PageLayout\Assets as LayoutAssets;

class Assets
{
    /** @var string */
    protected $key;

    /** @var string */
    protected $header;

    /** @var string */
    protected $footer;

    /** @var array */
    protected $cssFiles;

    /** @var array */
    protected $jsFiles;

    /** @var LayoutAssets|null */
    protected $layoutAssets;

    /**
     * Assets constructor.
     *
     * @param string            $key
     * @param string            $header
     * @param string            $footer
     * @param array             $cssFiles
     * @param array             $jsFiles
     * @param LayoutAssets|null $layoutAssets
     */
    public function __construct(
        string $key,
        string $header,
        string $footer,
        array $cssFiles,
        array $jsFiles,
        ?LayoutAssets $layoutAssets
    ) {
        $this->key          = $key;
        $this->header       = $header;
        $this->footer       = $footer;
        $this->cssFiles     = $cssFiles;
        $this->jsFiles      = $jsFiles;
        $this->layoutAssets = $layoutAssets;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     *
     * @return $this
     */
    public function setKey(string $key): Assets
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getHeader(): string
    {
        return $this->header;
    }

    /**
     * @param string $header
     *
     * @return $this
     */
    public function setHeader(string $header): Assets
    {
        $this->header = $header;

        return $this;
    }

    /**
     * @return string
     */
    public function getFooter(): string
    {
        return $this->footer;
    }

    /**
     * @param string $footer
     *
     * @return $this
     */
    public function setFooter(string $footer): Assets
    {
        $this->footer = $footer;

        return $this;
    }

    /**
     * @return array
     */
    public function getCssFiles(): array
    {
        return $this->cssFiles;
    }

    /**
     * @param array $cssFiles
     *
     * @return $this
     */
    public function setCssFiles(array $cssFiles): Assets
    {
        $this->cssFiles = $cssFiles;

        return $this;
    }

    /**
     * @return array
     */
    public function getJsFiles(): array
    {
        return $this->jsFiles;
    }

    /**
     * @param array $jsFiles
     *
     * @return $this
     */
    public function setJsFiles(array $jsFiles): Assets
    {
        $this->jsFiles = $jsFiles;

        return $this;
    }

    /**
     * @return LayoutAssets|null
     */
    public function getLayoutAssets(): ?LayoutAssets
    {
        return $this->layoutAssets;
    }

    /**
     * @param LayoutAssets|null $layoutAssets
     */
    public function setLayoutAssets(?LayoutAssets $layoutAssets): void
    {
        $this->layoutAssets = $layoutAssets;
    }
}
