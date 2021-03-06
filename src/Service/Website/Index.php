<?php

declare(strict_types=1);

namespace AbterPhp\Website\Service\Website;

use AbterPhp\Admin\Orm\UserRepo;
use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Html\Helper\StringHelper;
use AbterPhp\Framework\Template\Engine;
use AbterPhp\Website\Constant\Event;
use AbterPhp\Website\Domain\Entities\Page as Entity;
use AbterPhp\Website\Events\PageViewed;
use AbterPhp\Website\Orm\PageRepo;
use Casbin\Exceptions\CasbinException;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Orm\OrmException;

class Index
{
    /** @var Engine */
    protected $engine;

    /** @var PageRepo */
    protected $pageRepo;

    /** @var UserRepo */
    protected $userRepo;

    /** @var IEventDispatcher */
    protected $eventDispatcher;

    /**
     * Index constructor.
     *
     * @param Engine           $engine
     * @param PageRepo         $pageRepo
     * @param UserRepo         $userRepo
     * @param IEventDispatcher $eventDispatcher
     */
    public function __construct(
        Engine $engine,
        PageRepo $pageRepo,
        UserRepo $userRepo,
        IEventDispatcher $eventDispatcher
    ) {
        $this->engine          = $engine;
        $this->pageRepo        = $pageRepo;
        $this->userRepo        = $userRepo;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param string   $identifier
     * @param string[] $userGroupIdentifiers
     *
     * @return Entity
     * @throws OrmException
     * @throws CasbinException
     */
    public function getRenderedPage(string $identifier, array $userGroupIdentifiers): Entity
    {
        $page = $this->pageRepo->getWithLayout($identifier);

        if ($page === null) {
            throw new OrmException('page not found: ' . $identifier);
        }

        assert($page instanceof Entity);

        $pageEvent = new PageViewed($page, $userGroupIdentifiers);

        $this->eventDispatcher->dispatch(Event::PAGE_VIEWED, $pageEvent);
        if (!$pageEvent->isAllowed()) {
            throw new CasbinException(sprintf('viewing page not allowed" %s', $pageEvent->getPage()->getId()));
        }

        $page      = $pageEvent->getPage();
        $ledeLines = StringHelper::wrapByLines($page->getLede(), Html5::TAG_P);
        $lede      = StringHelper::wrapInTag($ledeLines, Html5::TAG_DIV, [Html5::ATTR_CLASS => 'lede']);

        $vars      = [
            'title' => $page->getTitle(),
            'lede'  => $lede,
        ];
        $templates = [
            'body'   => $page->getBody(),
            'layout' => $page->getLayout(),
        ];

        $renderedBody = $this->engine->run('page', $page->getIdentifier(), $templates, $vars);

        $page->setRenderedBody($renderedBody);

        return $page;
    }

    /**
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
