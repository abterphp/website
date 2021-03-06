<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Website;

use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\Framework\Config\EnvReader;
use AbterPhp\Framework\Constant\Session;
use AbterPhp\Framework\Http\Controllers\ControllerAbstract;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Website\Constant\Env;
use AbterPhp\Website\Constant\Route;
use AbterPhp\Website\Domain\Entities\Page;
use AbterPhp\Website\Domain\Entities\Page\Assets;
use AbterPhp\Website\Domain\Entities\Page\Meta;
use AbterPhp\Website\Domain\Entities\PageLayout\Assets as LayoutAssets;
use AbterPhp\Website\Service\Website\Index as IndexService;
use Casbin\Exceptions\CasbinException;
use League\Flysystem\FilesystemException;
use Opulence\Http\Responses\Response;
use Opulence\Http\Responses\ResponseHeaders;
use Opulence\Orm\OrmException;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Psr\Log\LoggerInterface;
use Throwable;

class Index extends ControllerAbstract
{
    const ERROR = 'error';

    /** @var ISession */
    protected $session;

    /** @var IndexService */
    protected $indexService;

    /** @var UrlGenerator */
    protected $urlGenerator;

    /** @var AssetManager */
    protected $assetManager;

    /** @var string */
    protected $baseUrl;

    /** @var string */
    protected $siteTitle;

    /**
     * Index constructor.
     *
     * @param FlashService $flashService
     * @param LoggerInterface $logger
     * @param ISession     $session
     * @param IndexService $indexService
     * @param UrlGenerator $urlGenerator
     * @param AssetManager $assetManager
     * @param EnvReader    $envReader
     */
    public function __construct(
        FlashService $flashService,
        LoggerInterface $logger,
        ISession $session,
        IndexService $indexService,
        UrlGenerator $urlGenerator,
        AssetManager $assetManager,
        EnvReader $envReader
    ) {
        $this->session      = $session;
        $this->indexService = $indexService;
        $this->urlGenerator = $urlGenerator;
        $this->assetManager = $assetManager;

        $this->baseUrl   = $envReader->get(Env::WEBSITE_BASE_URL);
        $this->siteTitle = $envReader->get(Env::WEBSITE_SITE_TITLE);

        parent::__construct($flashService, $logger);
    }

    /**
     * Shows the homepage
     *
     * @return Response
     * @throws Throwable
     */
    public function index(): Response
    {
        return $this->fallback('index');
    }

    /**
     * Shows the homepage
     *
     * @param string $identifier
     *
     * @return Response
     * @throws Throwable
     */
    public function fallback(string $identifier): Response
    {
        $this->view = $this->viewFactory->createView('contents/frontend/page');

        try {
            $page = $this->indexService->getRenderedPage($identifier, $this->getUserGroupIdentifiers());
        } catch (OrmException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        } catch (CasbinException $e) {
            $this->logger->info($e->getMessage(), $e->getTrace());
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage(), $e->getTrace());
        }

        if (empty($page)) {
            if ($identifier == static::ERROR) {
                return $this->fallback(static::ERROR);
            }

            $page = new Page('', '', '', '', '', '', false);
        }

        // @phan-suppress-next-line PhanTypeMismatchArgument
        $pageUrl     = $this->urlGenerator->createFromName(Route::FALLBACK, $identifier);
        $homepageUrl = $this->urlGenerator->createFromName(Route::INDEX);

        $this->view->setVar('body', $page->getRenderedBody());
        $this->view->setVar('siteTitle', $this->siteTitle);
        $this->view->setVar('pageUrl', $pageUrl);
        $this->view->setVar('homepageUrl', $homepageUrl);
        $this->view->setVar('classes', trim($page->getClasses()));

        $this->setMetaVars($page->getMeta());
        $this->setAssetsVars($page->getAssets());

        return $this->createResponse($page->getTitle());
    }

    /**
     * @return string[]
     */
    protected function getUserGroupIdentifiers(): array
    {
        $username = (string)$this->session->get(Session::USERNAME, '');
        if (!$username) {
            return [];
        }

        $userGroupIdentifiers = $this->indexService->getUserGroupIdentifiers($username);

        return $userGroupIdentifiers;
    }

    /**
     * @param Meta $meta
     */
    protected function setMetaVars(Meta $meta)
    {
        $this->view->setVar('metaDescription', $meta->getDescription());
        $this->view->setVar('metaKeywords', explode(',', $meta->getKeywords()));
        $this->view->setVar('metaCopyright', $meta->getCopyright());
        $this->view->setVar('metaAuthor', $meta->getAuthor());
        $this->view->setVar('metaRobots', $meta->getRobots());
        $this->view->setVar('metaOGDescription', $meta->getOGDescription());
        $this->view->setVar('metaOGTitle', $meta->getOGTitle());
        $this->view->setVar('metaOGImage', $meta->getOGImage());
    }

    /**
     * @param Assets|null $assets
     *
     * @throws FilesystemException
     */
    protected function setAssetsVars(?Assets $assets)
    {
        if ($assets === null) {
            return;
        }

        $origHeader = $this->view->hasVar('header') ? (string)$this->view->getVar('header') : '';
        $origFooter = $this->view->hasVar('footer') ? (string)$this->view->getVar('footer') : '';

        $this->view->setVar('header', $origHeader . $assets->getHeader());
        $this->view->setVar('footer', $origFooter . $assets->getFooter());
        $this->view->setVar('page', $assets->getKey());

        $key = $assets->getKey();
        foreach ($assets->getJsFiles() as $jsFile) {
            $this->assetManager->addJs($key, $jsFile);
        }
        foreach ($assets->getCssFiles() as $cssFile) {
            $this->assetManager->addCss($key, $cssFile);
        }

        $this->view->setVar('pageJs', count($assets->getJsFiles()));
        $this->view->setVar('pageCss', count($assets->getCssFiles()));

        $this->setLayoutAssetsVars($assets->getLayoutAssets());
    }

    /**
     * @param LayoutAssets|null $assets
     *
     * @throws FilesystemException
     */
    protected function setLayoutAssetsVars(?LayoutAssets $assets)
    {
        if ($assets === null) {
            return;
        }

        $origHeader = $this->view->hasVar('header') ? (string)$this->view->getVar('header') : '';
        $origFooter = $this->view->hasVar('footer') ? (string)$this->view->getVar('footer') : '';

        $this->view->setVar('header', $origHeader . $assets->getHeader());
        $this->view->setVar('footer', $origFooter . $assets->getFooter());
        $this->view->setVar('layout', $assets->getKey());

        $key = $assets->getKey();
        foreach ($assets->getJsFiles() as $jsFile) {
            $this->assetManager->addJs($key, $jsFile);
        }
        foreach ($assets->getCssFiles() as $cssFile) {
            $this->assetManager->addCss($key, $cssFile);
        }

        $this->view->setVar('layoutJs', count($assets->getJsFiles()));
        $this->view->setVar('layoutCss', count($assets->getCssFiles()));
    }

    /**
     * 404 page
     *
     * @return Response
     * @throws \Throwable
     */
    protected function notFound(): Response
    {
        $this->view = $this->viewFactory->createView('contents/frontend/404');

        $response = $this->createResponse('404 Page not Found');
        $response->setStatusCode(ResponseHeaders::HTTP_NOT_FOUND);

        return $response;
    }
}
