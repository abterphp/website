<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMappers;

use AbterPhp\Website\Domain\Entities\ContentList as Entity;
use Opulence\Orm\DataMappers\IDataMapper;

interface IContentListDataMapper extends IDataMapper
{
    /**
     * @param string $identifier
     *
     * @return Entity|null
     */
    public function getByIdentifier(string $identifier): ?Entity;

    /**
     * @param string[] $identifiers
     *
     * @return Entity[]
     */
    public function getByIdentifiers(array $identifiers): array;

    /**
     * @param int      $limitFrom
     * @param int      $pageSize
     * @param string[] $orders
     * @param array    $filters
     * @param array    $params
     *
     * @return Entity[]
     */
    public function getPage(int $limitFrom, int $pageSize, array $orders, array $filters, array $params): array;
}
