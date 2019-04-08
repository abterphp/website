<?php

declare(strict_types=1);

namespace AbterPhp\Website\Bootstrappers\Http\Controllers\Website;

use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Framework\Template\Engine;
use AbterPhp\Website\Constant\Env;
use AbterPhp\Website\Http\Controllers\Website\Index;
use AbterPhp\Website\Orm\PageRepo;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;
use Opulence\Routing\Urls\UrlGenerator;

class IndexBootstrapper extends Bootstrapper implements ILazyBootstrapper
{
    /**
     * @return array
     */
    public function getBindings(): array
    {
        return [
            Index::class,
        ];
    }

    /**
     * @param IContainer $container
     */
    public function registerBindings(IContainer $container)
    {
        $flashService   = $container->resolve(FlashService::class);
        $templateEngine = $container->resolve(Engine::class);
        $pageRepo       = $container->resolve(PageRepo::class);
        $urlGenerator   = $container->resolve(UrlGenerator::class);
        $assetManager   = $container->resolve(AssetManager::class);
        $baseUrl        = getenv(Env::WEBSITE_BASE_URL) ?: null;
        $siteTitle      = getenv(Env::WEBSITE_SITE_TITLE);

        $login = new Index(
            $flashService,
            $templateEngine,
            $pageRepo,
            $urlGenerator,
            $assetManager,
            $baseUrl,
            $siteTitle
        );

        $container->bindInstance(Index::class, $login);
    }
}
