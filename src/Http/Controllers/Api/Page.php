<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Api;

use AbterPhp\Framework\Http\Controllers\Admin\ApiAbstract;
use AbterPhp\Website\Service\Execute\Page as RepoService;
use Psr\Log\LoggerInterface;

class Page extends ApiAbstract
{
    const ENTITY_SINGULAR = 'page';
    const ENTITY_PLURAL   = 'pages';

    /**
     * Page constructor.
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

        $data['meta']   = !empty($data['meta']) ? $data['meta'] : [];
        $data['assets'] = !empty($data['assets']) ? $data['assets'] : [];

        $data = array_merge($data, $data['meta'], $data['assets']);

        unset($data['meta'], $data['assets']);

        return $data;
    }
}
