<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Api;

use AbterPhp\Framework\Http\Controllers\Admin\ApiAbstract;
use AbterPhp\Website\Service\Execute\Block as RepoService;
use Psr\Log\LoggerInterface;

class Block extends ApiAbstract
{
    const ENTITY_SINGULAR = 'block';
    const ENTITY_PLURAL   = 'blocks';

    /**
     * Block constructor.
     *
     * @param LoggerInterface $logger
     * @param RepoService     $repoService
     */
    public function __construct(LoggerInterface $logger, RepoService $repoService)
    {
        parent::__construct($logger, $repoService);
    }
}
