<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Admin\Execute;

use AbterPhp\Framework\Http\Controllers\Admin\ExecuteAbstract;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Website\Service\Execute\PageCategory as RepoService;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Psr\Log\LoggerInterface;

class PageCategory extends ExecuteAbstract
{
    const ENTITY_SINGULAR = 'pageCategory';
    const ENTITY_PLURAL   = 'pageCategories';

    const ENTITY_TITLE_SINGULAR = 'website:pageCategory';
    const ENTITY_TITLE_PLURAL   = 'website:pageCategories';

    /**
     * PageCategory constructor.
     *
     * @param FlashService    $flashService
     * @param ITranslator     $translator
     * @param UrlGenerator    $urlGenerator
     * @param RepoService     $repoService
     * @param ISession        $session
     * @param LoggerInterface $logger
     */
    public function __construct(
        FlashService $flashService,
        ITranslator $translator,
        UrlGenerator $urlGenerator,
        RepoService $repoService,
        ISession $session,
        LoggerInterface $logger
    ) {
        parent::__construct(
            $flashService,
            $translator,
            $urlGenerator,
            $repoService,
            $session,
            $logger
        );
    }
}