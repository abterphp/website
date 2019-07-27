<?php

declare(strict_types=1);

namespace AbterPhp\Website\Service\Website;

use AbterPhp\Admin\Orm\UserRepo;
use AbterPhp\Framework\Template\Engine;
use AbterPhp\Website\Constant\Event;
use AbterPhp\Website\Domain\Entities\Page as Entity;
use AbterPhp\Website\Events\PageViewed;
use AbterPhp\Website\Orm\PageRepo;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Orm\OrmException;

class Index
{
    /** @var Engine */
    protected $templateEngine;

    /** @var PageRepo */
    protected $pageRepo;

    /** @var IEventDispatcher */
    protected $eventDispatcher;

    /**
     * Index constructor.
     *
     * @param Engine           $templateEngine
     * @param PageRepo         $pageRepo
     * @param UserRepo         $userRepo
     * @param IEventDispatcher $eventDispatcher
     */
    public function __construct(
        Engine $templateEngine,
        PageRepo $pageRepo,
        UserRepo $userRepo,
        IEventDispatcher $eventDispatcher
    ) {
        $this->templateEngine  = $templateEngine;
        $this->pageRepo        = $pageRepo;
        $this->userRepo        = $userRepo;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param string   $identifier
     * @param string[] $userGroupIdentifiers
     *
     * @return Entity|null
     */
    public function getRenderedPage(string $identifier, array $userGroupIdentifiers): ?Entity
    {
        try {
            $page = $this->pageRepo->getWithLayout($identifier);
        } catch (OrmException $exc) {
            return null;
        }

        $pageEvent = new PageViewed($page, $userGroupIdentifiers);

        $this->eventDispatcher->dispatch(Event::PAGE_VIEWED, $pageEvent);
        if (!$pageEvent->isAllowed()) {
            return null;
        }

        $page = $pageEvent->getPage();

        $vars      = ['title' => $page->getTitle()];
        $templates = [
            'body'   => $page->getBody(),
            'layout' => $page->getLayout(),
        ];

        $renderedBody = $this->templateEngine->run('page', $page->getIdentifier(), $templates, $vars);

        $page->setRenderedBody($renderedBody);

        return $page;
    }

    /**
     * @param string      $identifier
     * @param string|null $visitorUsername
     *
     * @return string[]
     */
    public function getUserGroupIdentifiers(?string $visitorUsername): array
    {
        try {
            $user = $this->userRepo->getByUsername($visitorUsername);
        } catch (OrmException $exc) {
            return [];
        }

        $userGroupIdentifiers = [];
        foreach ($user->getUserGroups() as $userGroup) {
            $userGroupIdentifiers[] = $userGroup->getIdentifier();
        }


        return $userGroupIdentifiers;
    }
}