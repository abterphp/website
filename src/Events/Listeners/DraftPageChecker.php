<?php

declare(strict_types=1);

namespace AbterPhp\Website\Events\Listeners;

use AbterPhp\Framework\Authorization\Constant\Role;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Website\Events\PageViewed;
use Casbin\Enforcer;

class DraftPageChecker
{
    const RESOURCE_IDENTIFIER = 'admin_resource_pages';

    /** @var Enforcer */
    protected $enforcer;

    /** @var ITranslator */
    protected $translator;

    /**
     * NavigationBuilder constructor.
     *
     * @param Enforcer    $enforcer
     * @param ITranslator $translator
     */
    public function __construct(Enforcer $enforcer, ITranslator $translator)
    {
        $this->enforcer   = $enforcer;
        $this->translator = $translator;
    }

    /**
     * @param PageViewed $event
     *
     * @throws \Casbin\Exceptions\CasbinException
     */
    public function handle(PageViewed $event)
    {
        $page = $event->getPage();

        // Page is not a draft
        if (!$page->isDraft()) {
            return;
        }

        foreach ($event->getUserGroupIdentifiers() as $userGroupIdentifier) {
            if ($this->enforcer->enforce($userGroupIdentifier, static::RESOURCE_IDENTIFIER, Role::READ)) {
                return $this->handleAllowed($event);
            }
        }

        return $this->handleNotAllowed($event);
    }

    /**
     * @param PageViewed $event
     */
    protected function handleAllowed(PageViewed $event)
    {
        $page = clone $event->getPage();

        $page->setTitle(
            sprintf(
                '%s - %s',
                $this->translator->translate('website:pageDraftAllowedTitle'),
                $page->getTitle()
            )
        );
        $page->setBody(
            sprintf(
                "%s\n%s",
                $this->translator->translate('website:pageDraftAllowedBody'),
                $page->getBody()
            )
        );

        $event->setPage($page);
        $event->setIsAllowed(true);
    }

    /**
     * @param PageViewed $event
     */
    protected function handleNotAllowed(PageViewed $event)
    {
        $event->setIsAllowed(false);
    }
}
