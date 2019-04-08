<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Website;

use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\Framework\Http\Controllers\ControllerAbstract;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Framework\Template\Engine;
use AbterPhp\Website\Constant\Routes;
use AbterPhp\Website\Domain\Entities\Page\Assets;
use AbterPhp\Website\Domain\Entities\Page\Meta;
use AbterPhp\Website\Domain\Entities\PageLayout\Assets as LayoutAssets;
use AbterPhp\Website\Orm\PageRepo;
use Opulence\Http\Responses\Response;
use Opulence\Http\Responses\ResponseHeaders;
use Opulence\Orm\OrmException;
use Opulence\Routing\Urls\UrlGenerator;

class Index extends ControllerAbstract
{
    /** @var Engine */
    protected $templateEngine;

    /** @var PageRepo */
    protected $pageRepo;

    /** @var UrlGenerator */
    protected $urlGenerator;

    /** @var AssetManager */
    protected $assetManager;

    /** @var string|null */
    protected $baseUrl;

    /** @var string */
    protected $siteTitle;

    /**
     * Index constructor.
     *
     * @param FlashService $flashService
     * @param Engine       $templateEngine
     * @param PageRepo     $pageRepo
     * @param UrlGenerator $urlGenerator
     * @param AssetManager $assetManager
     * @param string|null  $baseUrl
     * @param string       $siteTitle
     */
    public function __construct(
        FlashService $flashService,
        Engine $templateEngine,
        PageRepo $pageRepo,
        UrlGenerator $urlGenerator,
        AssetManager $assetManager,
        ?string $baseUrl,
        string $siteTitle
    ) {
        $this->templateEngine = $templateEngine;
        $this->pageRepo       = $pageRepo;
        $this->urlGenerator   = $urlGenerator;
        $this->assetManager   = $assetManager;

        $this->baseUrl   = $baseUrl;
        $this->siteTitle = $siteTitle;

        parent::__construct($flashService);
    }

    /**
     * Shows the homepage
     *
     * @return Response The response
     */
    public function homePage(): Response
    {
        return $this->otherPage('index');
    }

    /**
     * Shows the homepage
     *
     * @return Response The response
     */
    public function otherPage(string $identifier): Response
    {
        $this->view = $this->viewFactory->createView('contents/frontend/page');

        try {
            $page = $this->pageRepo->getWithLayout($identifier);
        } catch (OrmException $exc) {
            return $this->notFound();
        }

        $vars      = ['title' => $page->getTitle()];
        $templates = [
            'body'   => $page->getBody(),
            'layout' => $page->getLayout(),
        ];

        $body = $this->templateEngine->run('page', $page->getIdentifier(), $templates, $vars);

        $pageUrl     = $this->urlGenerator->createFromName(Routes::ROUTE_PAGE_OTHER, $identifier);
        $homepageUrl = $this->urlGenerator->createFromName(Routes::ROUTE_HOME);

        $this->view->setVar('body', $body);
        $this->view->setVar('siteTitle', $this->siteTitle);
        $this->view->setVar('pageUrl', $pageUrl);
        $this->view->setVar('homepageUrl', $homepageUrl);

        $this->setMetaVars($page->getMeta());
        $this->setAssetsVars($page->getAssets());

        return $this->createResponse($page->getTitle());
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

        $this->setLayoutAssetsVars($assets->getLayoutAssets());
    }

    /**
     * @param LayoutAssets|null $assets
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
    }

    /**
     * @param string $route
     *
     * @return string
     * @throws \Opulence\Routing\Urls\URLException
     */
    protected function getCanonicalUrl(string $route): string
    {
        $path = $this->urlGenerator->createFromName($route);

        return $this->baseUrl . ltrim($path, '/');
    }

    /**
     * @return string
     */
    protected function getBaseUrl(): string
    {
        if ($this->baseUrl === null) {
            $this->baseUrl = sprintf(
                '%s://%s/',
                $this->request->getServer()->get('REQUEST_SCHEME'),
                $this->request->getServer()->get('SERVER_NAME')
            );
        }

        return $this->baseUrl;
    }

    /**
     * 404 page
     *
     * @return Response The response
     */
    protected function notFound(): Response
    {
        $this->view = $this->viewFactory->createView('contents/frontend/404');

        $response = $this->createResponse('404 Page not Found');
        $response->setStatusCode(ResponseHeaders::HTTP_NOT_FOUND);

        return $response;
    }
}
