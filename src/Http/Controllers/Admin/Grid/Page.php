<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Admin\Grid;

use AbterPhp\Admin\Http\Controllers\Admin\GridAbstract;
use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Website\Service\RepoGrid\Page as RepoGrid;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Routing\Urls\UrlGenerator;
use Psr\Log\LoggerInterface;

class Page extends GridAbstract
{
    const ENTITY_PLURAL   = 'pages';
    const ENTITY_SINGULAR = 'page';

    const ENTITY_TITLE_PLURAL = 'website:pages';

    const ROUTING_PATH = 'pages';

    /** @var string */
    protected $resource = 'pages';

    /**
     * Page constructor.
     *
     * @param FlashService     $flashService
     * @param LoggerInterface  $logger
     * @param ITranslator      $translator
     * @param UrlGenerator     $urlGenerator
     * @param AssetManager     $assets
     * @param RepoGrid         $repoGrid
     * @param IEventDispatcher $eventDispatcher
     */
    public function __construct(
        FlashService $flashService,
        LoggerInterface $logger,
        ITranslator $translator,
        UrlGenerator $urlGenerator,
        AssetManager $assets,
        RepoGrid $repoGrid,
        IEventDispatcher $eventDispatcher
    ) {
        parent::__construct(
            $flashService,
            $logger,
            $translator,
            $urlGenerator,
            $assets,
            $repoGrid,
            $eventDispatcher
        );
    }
}
