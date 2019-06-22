<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Api;

use AbterPhp\Framework\Config\Provider as ConfigProvider;
use AbterPhp\Framework\Databases\Queries\FoundRows;
use AbterPhp\Framework\Http\Controllers\ApiAbstract;
use AbterPhp\Framework\Template\Engine as TemplateEngine;
use AbterPhp\Website\Domain\Entities\Page as Entity;
use AbterPhp\Website\Service\Execute\Page as RepoService;
use Opulence\Http\Responses\Response;
use Psr\Log\LoggerInterface;

class Page extends ApiAbstract
{
    const ENTITY_SINGULAR = 'page';
    const ENTITY_PLURAL   = 'pages';

    /** @var TemplateEngine */
    protected $templateEngine;

    /**
     * Page constructor.
     *
     * @param LoggerInterface $logger
     * @param RepoService     $repoService
     * @param FoundRows       $foundRows
     * @param ConfigProvider  $configProvider
     * @param TemplateEngine  $templateEngine
     */
    public function __construct(
        LoggerInterface $logger,
        RepoService $repoService,
        FoundRows $foundRows,
        ConfigProvider $configProvider,
        TemplateEngine $templateEngine
    ) {
        parent::__construct($logger, $repoService, $foundRows, $configProvider);

        $this->templateEngine = $templateEngine;
    }

    /**
     * @param string $entityId
     *
     * @return Response
     */
    public function get(string $entityId): Response
    {
        if ($this->request->getQuery()->get('embed') === 'rendered') {
            return $this->getWithRendered($entityId);
        }

        return parent::get($entityId);
    }

    /**
     * @param string $entityId
     *
     * @return Response
     */
    public function getWithRendered(string $entityId): Response
    {
        try {
            /** @var Entity $entity */
            $entity = $this->repoService->retrieveEntityWithLayout($entityId);

            $vars      = ['title' => $entity->getTitle()];
            $templates = [
                'body'   => $entity->getBody(),
                'layout' => $entity->getLayout(),
            ];

            $renderedBody = $this->templateEngine->run('page', $entity->getIdentifier(), $templates, $vars);

            $entity->setRenderedBody($renderedBody);
        } catch (\Exception $e) {
            $msg = sprintf(static::LOG_MSG_GET_FAILURE, static::ENTITY_SINGULAR, $entityId);

            return $this->handleException($msg, $e);
        }

        return $this->handleGetSuccess($entity);
    }

    /**
     * @return array
     */
    public function getSharedData(): array
    {
        $data = $this->request->getJsonBody();

        $data['meta']   = !empty($data['meta']) ? $data['meta'] : [];
        $data['assets'] = !empty($data['assets']) ? $data['assets'] : [];

        $data = array_merge($data, $data['meta'], $data['assets']);

        unset($data['meta'], $data['assets']);

        return $data;
    }
}
