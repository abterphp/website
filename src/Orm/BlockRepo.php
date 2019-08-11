<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm;

use AbterPhp\Framework\Orm\IGridRepo;
use AbterPhp\Website\Domain\Entities\Block as Entity;
use AbterPhp\Website\Orm\DataMappers\BlockSqlDataMapper; // @phan-suppress-current-line PhanUnreferencedUseNormal
use Opulence\Orm\Repositories\Repository;

class BlockRepo extends Repository implements IGridRepo
{
    /**
     * @param int      $limitFrom
     * @param int      $pageSize
     * @param string[] $orders
     * @param array    $conditions
     * @param array    $params
     *
     * @return Entity[]
     * @throws \Opulence\Orm\OrmException
     */
    public function getPage(int $limitFrom, int $pageSize, array $orders, array $conditions, array $params): array
    {
        /** @see BlockSqlDataMapper::getPage() */
        return $this->getFromDataMapper('getPage', [$limitFrom, $pageSize, $orders, $conditions, $params]);
    }

    /**
     * @param string $identifier
     *
     * @return Entity|null
     * @throws \Opulence\Orm\OrmException
     */
    public function getByIdentifier(string $identifier): ?Entity
    {
        /** @see BlockSqlDataMapper::getByIdentifier() */
        return $this->getFromDataMapper('getByIdentifier', [$identifier]);
    }

    /**
     * @param array $identifiers
     *
     * @return array
     * @throws \Opulence\Orm\OrmException
     */
    public function getWithLayoutByIdentifiers(array $identifiers): array
    {
        /** @see BlockSqlDataMapper::getWithLayoutByIdentifiers() */
        return $this->getFromDataMapper('getWithLayoutByIdentifiers', [$identifiers]);
    }
}
