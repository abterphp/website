<?php

declare(strict_types=1);

namespace AbterPhp\Website\Domain\Entities\Page;

class Meta
{
    /** @var string */
    protected $description;

    /** @var string */
    protected $robots;

    /** @var string */
    protected $author;

    /** @var string */
    protected $copyright;

    /** @var string */
    protected $keywords;

    /** @var string */
    protected $oGTitle;

    /** @var string */
    protected $oGImage;

    /** @var string */
    protected $oGDescription;

    /**
     * Page constructor.
     *
     * @param string   $description
     * @param string   $robots
     * @param string   $author
     * @param string   $copyright
     * @param string   $keywords
     * @param string   $oGTitle
     * @param string   $oGImage
     * @param string   $oGDescription
     */
    public function __construct(
        string $description,
        string $robots,
        string $author,
        string $copyright,
        string $keywords,
        string $oGTitle,
        string $oGImage,
        string $oGDescription
    ) {
        $this->description   = $description;
        $this->robots        = $robots;
        $this->author        = $author;
        $this->copyright     = $copyright;
        $this->keywords      = $keywords;
        $this->oGTitle       = $oGTitle;
        $this->oGImage       = $oGImage;
        $this->oGDescription = $oGDescription;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription(string $description): Meta
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getRobots(): string
    {
        return $this->robots;
    }

    /**
     * @param string $robots
     *
     * @return $this
     */
    public function setRobots(string $robots): Meta
    {
        $this->robots = $robots;

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     *
     * @return $this
     */
    public function setAuthor(string $author): Meta
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return string
     */
    public function getCopyright(): string
    {
        return $this->copyright;
    }

    /**
     * @param string $copyright
     *
     * @return $this
     */
    public function setCopyright(string $copyright): Meta
    {
        $this->copyright = $copyright;

        return $this;
    }

    /**
     * @return string
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * @param string $keywords
     *
     * @return $this
     */
    public function setKeywords(string $keywords): Meta
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * @return string
     */
    public function getOGTitle(): string
    {
        return $this->oGTitle;
    }

    /**
     * @param string $oGTitle
     *
     * @return $this
     */
    public function setOGTitle(string $oGTitle): Meta
    {
        $this->oGTitle = $oGTitle;

        return $this;
    }

    /**
     * @return string
     */
    public function getOGImage(): string
    {
        return $this->oGImage;
    }

    /**
     * @param string $oGImage
     *
     * @return $this
     */
    public function setOGImage(string $oGImage): Meta
    {
        $this->oGImage = $oGImage;

        return $this;
    }

    /**
     * @return string
     */
    public function getOGDescription(): string
    {
        return $this->oGDescription;
    }

    /**
     * @param string $oGDescription
     *
     * @return $this
     */
    public function setOGDescription(string $oGDescription): Meta
    {
        $this->oGDescription = $oGDescription;

        return $this;
    }
}
