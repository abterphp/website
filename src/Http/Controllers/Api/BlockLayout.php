<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Api;

use AbterPhp\Admin\Http\Controllers\ApiAbstract;
use AbterPhp\Framework\Config\EnvReader;
use AbterPhp\Framework\Databases\Queries\FoundRows;
use AbterPhp\Website\Service\Execute\BlockLayout as RepoService;
use Psr\Log\LoggerInterface;

class BlockLayout extends ApiAbstract
{
    const ENTITY_SINGULAR = 'blockLayout';
    const ENTITY_PLURAL   = 'blockLayouts';

    /**
     * BlockLayout constructor.
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
