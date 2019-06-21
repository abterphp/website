<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Api;

use AbterPhp\Framework\Config\Provider as ConfigProvider;
use AbterPhp\Framework\Databases\Queries\FoundRows;
use AbterPhp\Framework\Http\Controllers\ApiAbstract;
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
     * @param ConfigProvider  $configProvider
     */
    public function __construct(
        LoggerInterface $logger,
        RepoService $repoService,
        FoundRows $foundRows,
        ConfigProvider $configProvider
    ) {
        parent::__construct($logger, $repoService, $foundRows, $configProvider);
    }
}
