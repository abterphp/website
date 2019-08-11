<?php

declare(strict_types=1);

namespace AbterPhp\Website\Databases\Queries;

use Opulence\Databases\ConnectionPools\ConnectionPool;
use Opulence\QueryBuilders\Conditions\ConditionFactory;
use Opulence\QueryBuilders\MySql\QueryBuilder;

/** @phan-file-suppress PhanTypeMismatchArgument */

class PageCategoryCache
{
    /** @var ConnectionPool */
    protected $connectionPool;

    /**
     * PageCategoryCache constructor.
     *
     * @param ConnectionPool $connectionPool
     */
    public function __construct(ConnectionPool $connectionPool)
    {
        $this->connectionPool = $connectionPool;
    }

    /**
     * @param string[] $identifiers
     * @param string   $cacheTime
     *
     * @return bool
     * @throws \Opulence\QueryBuilders\InvalidQueryException
     */
    public function hasAnyChangedSince(array $identifiers, string $cacheTime): bool
    {
        $conditions = new ConditionFactory();
        $query      = (new QueryBuilder())
            ->select('COUNT(*) AS count')
            ->from('pages')
            ->leftJoin('page_categories', 'page_categories', 'page_categories.id = pages.category_id')
            ->where('pages.deleted = 0')
            ->andWhere($conditions->in('page_categories.identifier', $identifiers))
            ->andWhere('pages.updated_at > ?')
            ->addUnnamedPlaceholderValue($cacheTime, \PDO::PARAM_STR)
        ;

        $connection = $this->connectionPool->getReadConnection();
        $statement  = $connection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        if (!$statement->execute()) {
            return true;
        }

        return $statement->fetchColumn() > 0;
    }
}
