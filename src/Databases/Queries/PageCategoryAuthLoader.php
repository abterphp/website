<?php

declare(strict_types=1);

namespace AbterPhp\Website\Databases\Queries;

use AbterPhp\Framework\Databases\Queries\IAuthLoader;
use Opulence\Databases\ConnectionPools\ConnectionPool;
use Opulence\QueryBuilders\MySql\QueryBuilder;

class PageCategoryAuthLoader implements IAuthLoader
{
    /** @var ConnectionPool */
    protected $connectionPool;

    /**
     * BlockCache constructor.
     *
     * @param ConnectionPool $connectionPool
     */
    public function __construct(ConnectionPool $connectionPool)
    {
        $this->connectionPool = $connectionPool;
    }

    /**
     * @return array|bool
     */
    public function loadAll()
    {
        $query = (new QueryBuilder())
            ->select('ug.identifier AS v0', 'pc.identifier AS v1')
            ->from('user_groups_page_categories', 'ugpc')
            ->innerJoin('page_categories', 'pc', 'ugpc.page_category_id = pc.id AND pc.deleted = 0')
            ->innerJoin('user_groups', 'ug', 'ugpc.user_group_id = ug.id AND ug.deleted = 0')
        ;

        $connection = $this->connectionPool->getReadConnection();
        $statement  = $connection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        if (!$statement->execute()) {
            return true;
        }

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}
