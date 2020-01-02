<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMappers;

use AbterPhp\Website\Domain\Entities\ContentListItem as Entity;
use Opulence\Orm\DataMappers\IDataMapper;

interface IContentListItemDataMapper extends IDataMapper
{
    /**
     * @param string $listId
     *
     * @return Entity[]
     */
    public function getByListId(string $listId): array;

    /**
     * @param string[] $listIds
     *
     * @return Entity[]
     */
    public function getByListIds(array $listIds): array;

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
