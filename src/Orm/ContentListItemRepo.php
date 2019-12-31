<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm;

use AbterPhp\Framework\Orm\IGridRepo;
use AbterPhp\Website\Domain\Entities\ContentListItem as Entity;
use AbterPhp\Website\Orm\DataMappers\ContentListItemSqlDataMapper as DataMapper; // @phan-suppress-current-line PhanUnreferencedUseNormal
use Opulence\Orm\Repositories\Repository;

class ContentListItemRepo extends Repository implements IGridRepo
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
        /** @see DataMapper::getPage() */
        return $this->getFromDataMapper('getPage', [$limitFrom, $pageSize, $orders, $conditions, $params]);
    }

    /**
     * @param string $listId
     *
     * @return Entity[]
     * @throws \Opulence\Orm\OrmException
     */
    public function getByListId(string $listId): array
    {
        /** @see DataMapper::getByListId() */
        return $this->getFromDataMapper('getByListId', [$listId]);
    }
}
