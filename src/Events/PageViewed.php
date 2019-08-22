<?php

declare(strict_types=1);

namespace AbterPhp\Website\Events;

use AbterPhp\Website\Domain\Entities\Page as Entity;

class PageViewed
{
    /** @var Entity */
    private $page;

    /** @var bool */
    private $isAllowed = false;

    /** @var string[] */
    private $userGroupIdentifiers = [];

    /**
     * PageViewed constructor.
     *
     * @param Entity $page
     * @param array   $userGroupIdentifiers
     */
    public function __construct(Entity $page, array $userGroupIdentifiers)
    {
        $this->setPage($page);

        $this->userGroupIdentifiers = $userGroupIdentifiers;
    }

    /**
     * @return Entity
     */
    public function getPage(): Entity
    {
        return $this->page;
    }

    /**
     * @param Entity $page
     *
     * @return $this
     */
    public function setPage(Entity $page): PageViewed
    {
        $this->page      = $page;
        $this->isAllowed = !$page->isDraft();

        return $this;
    }

    /**
     * @return bool
     */
    public function isAllowed(): bool
    {
        return $this->isAllowed;
    }

    /**
     * @return $this
     */
    public function setIsAllowed(): self
    {
        $this->isAllowed = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function setIsNotAllowed(): self
    {
        $this->isAllowed = false;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getUserGroupIdentifiers(): array
    {
        return $this->userGroupIdentifiers;
    }
}
