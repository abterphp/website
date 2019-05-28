<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Api;

use AbterPhp\Framework\Http\Controllers\Admin\ApiAbstract;
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
     */
    public function __construct(LoggerInterface $logger, RepoService $repoService)
    {
        parent::__construct($logger, $repoService);
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
