<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Api;

use AbterPhp\Framework\Config\EnvReader;
use AbterPhp\Framework\Databases\Queries\FoundRows;
use AbterPhp\Framework\Http\Controllers\ApiAbstract;
use AbterPhp\Website\Service\Execute\PageCategory as RepoService;
use Psr\Log\LoggerInterface;

class PageCategory extends ApiAbstract
{
    const ENTITY_SINGULAR = 'pageCategory';
    const ENTITY_PLURAL   = 'pageCategorys';

    /**
     * PageCategory constructor.
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
}
