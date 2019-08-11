<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Admin\Execute;

use AbterPhp\Admin\Http\Controllers\Admin\ExecuteAbstract;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Website\Service\Execute\Page as RepoService;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Psr\Log\LoggerInterface;

class Page extends ExecuteAbstract
{
    const ENTITY_SINGULAR = 'page';
    const ENTITY_PLURAL   = 'pages';

    const ENTITY_TITLE_SINGULAR = 'website:page';
    const ENTITY_TITLE_PLURAL   = 'website:pages';

    /**
     * Page constructor.
     *
     * @param FlashService    $flashService
     * @param ITranslator     $translator
     * @param UrlGenerator    $urlGenerator
     * @param LoggerInterface $logger
     * @param RepoService     $repoService
     * @param ISession        $session
     */
    public function __construct(
        FlashService $flashService,
        ITranslator $translator,
        UrlGenerator $urlGenerator,
        LoggerInterface $logger,
        RepoService $repoService,
        ISession $session
    ) {
        parent::__construct(
            $flashService,
            $translator,
            $urlGenerator,
            $logger,
            $repoService,
            $session
        );
    }
}
