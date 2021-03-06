<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm;

use AbterPhp\Framework\Orm\IGridRepo;
use AbterPhp\Website\Domain\Entities\Page as Entity;
use AbterPhp\Website\Orm\DataMappers\PageSqlDataMapper as DataMapper; // @phan-suppress-current-line PhanUnreferencedUseNormal
use Opulence\Orm\OrmException;
use Opulence\Orm\Repositories\Repository;

class PageRepo extends Repository implements IGridRepo
{
    /**
     * @param int      $limitFrom
     * @param int      $pageSize
     * @param string[] $orders
     * @param array    $conditions
     * @param array    $params
     *
     * @return Entity[]
     * @throws OrmException
     */
    public function getPage(int $limitFrom, int $pageSize, array $orders, array $conditions, array $params): array
    {
        /** @see DataMapper::getPage() */
        return $this->getFromDataMapper('getPage', [$limitFrom, $pageSize, $orders, $conditions, $params]);
    }

    /**
     * @param string $identifier
     *
     * @return Entity|null
     * @throws OrmException
     */
    public function getByIdentifier(string $identifier): ?Entity
    {
        /** @see DataMapper::getByIdentifier() */
        return $this->getFromDataMapper('getByIdentifier', [$identifier]);
    }

    /**
     * @param string $identifier
     *
     * @return Entity|null
     * @throws OrmException
     */
    public function getWithLayout(string $identifier): ?Entity
    {
        /** @see DataMapper::getWithLayout() */
        return $this->getFromDataMapper('getWithLayout', [$identifier]);
    }

    /**
     * @param string[] $identifiers
     *
     * @return Entity[]
     * @throws OrmException
     */
    public function getByCategoryIdentifiers(array $identifiers): array
    {
        /** @see DataMapper::getByCategoryIdentifiers() */
        return $this->getFromDataMapper('getByCategoryIdentifiers', [$identifiers]);
    }
}
