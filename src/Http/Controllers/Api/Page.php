<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Api;

use AbterPhp\Admin\Http\Controllers\ApiAbstract;
use AbterPhp\Framework\Config\EnvReader;
use AbterPhp\Framework\Databases\Queries\FoundRows;
use AbterPhp\Website\Service\Execute\Page as RepoService;
use AbterPhp\Website\Service\Website\Index as IndexService;
use Opulence\Http\Responses\Response;
use Opulence\Orm\OrmException;
use Psr\Log\LoggerInterface;

class Page extends ApiAbstract
{
    const ENTITY_SINGULAR = 'page';
    const ENTITY_PLURAL   = 'pages';

    /** @var IndexService */
    protected $indexService;

    /**
     * Page constructor.
     *
     * @param LoggerInterface $logger
     * @param RepoService     $repoService
     * @param FoundRows       $foundRows
     * @param EnvReader       $envReader
     * @param IndexService    $indexService
     */
    public function __construct(
        LoggerInterface $logger,
        RepoService $repoService,
        FoundRows $foundRows,
        EnvReader $envReader,
        IndexService $indexService
    ) {
        parent::__construct($logger, $repoService, $foundRows, $envReader);

        $this->indexService = $indexService;
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
            $userGroupIdentifiers = $this->indexService->getUserGroupIdentifiers($this->getUserIdentifier());

            $entity = $this->indexService->getRenderedPage($entityId, $userGroupIdentifiers);
        } catch (\Exception $e) {
            $msg = sprintf(static::LOG_MSG_GET_FAILURE, static::ENTITY_SINGULAR, $entityId);

            return $this->handleException($msg, $e);
        }

        if ($entity) {
            return $this->handleGetSuccess($entity);
        }

        try {
            $this->repoService->retrieveEntity($entityId);
        } catch (OrmException $e) {
            return $this->handleNotFound();
        }

        return $this->handleUnauthorized();
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
