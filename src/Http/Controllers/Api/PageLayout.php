<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Api;

use AbterPhp\Admin\Http\Controllers\ApiAbstract;
use AbterPhp\Framework\Config\EnvReader;
use AbterPhp\Framework\Databases\Queries\FoundRows;
use AbterPhp\Website\Service\Execute\PageLayout as RepoService;
use Psr\Log\LoggerInterface;

class PageLayout extends ApiAbstract
{
    const ENTITY_SINGULAR = 'pageLayout';
    const ENTITY_PLURAL   = 'pageLayouts';

    /**
     * PageLayout constructor.
     *
     * @param LoggerInterface $logger
     * @param RepoService     $repoService
     * @param FoundRows       $foundRows
     * @param EnvReader       $envReader
     */
    public function __construct(
        LoggerInterface $logger,
        RepoService $repoService,
        FoundRows $foundRows,
        EnvReader $envReader
    ) {
        parent::__construct($logger, $repoService, $foundRows, $envReader);
    }

    /**
     * @return array
     */
    public function getSharedData(): array
    {
        $data = $this->request->getJsonBody();

        $data['assets'] = !empty($data['assets']) ? $data['assets'] : [];

        $data = array_merge($data, $data['assets']);

        unset($data['assets']);

        return $data;
    }
}
