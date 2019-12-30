<?php

declare(strict_types=1);

namespace AbterPhp\Website\Databases\Queries;

use AbterPhp\Admin\Databases\Queries\IAuthLoader;
use AbterPhp\Admin\Exception\Database;
use Opulence\Databases\ConnectionPools\ConnectionPool;
use Opulence\QueryBuilders\MySql\QueryBuilder;

/** @phan-file-suppress PhanTypeMismatchArgument */

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
     * @return array
     */
    public function loadAll(): array
    {
        $query = (new QueryBuilder())
            ->select('ug.identifier AS v0', 'pc.identifier AS v1')
            ->from('user_groups_page_categories', 'ugpc')
            ->innerJoin('page_categories', 'pc', 'ugpc.page_category_id = pc.id AND pc.deleted_at IS NULL')
            ->innerJoin('user_groups', 'ug', 'ugpc.user_group_id = ug.id AND ug.deleted_at IS NULL')
        ;

        $connection = $this->connectionPool->getReadConnection();
        $statement  = $connection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        if (!$statement->execute()) {
            throw new Database($statement->errorInfo());
        }

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}
