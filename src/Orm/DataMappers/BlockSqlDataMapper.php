<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMappers;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Website\Domain\Entities\Block as Entity;
use Opulence\Orm\DataMappers\SqlDataMapper;
use Opulence\QueryBuilders\Conditions\ConditionFactory;
use Opulence\QueryBuilders\MySql\QueryBuilder;
use Opulence\QueryBuilders\MySql\SelectQuery;
use PDO;

/** @phan-file-suppress PhanTypeMismatchArgument */

class BlockSqlDataMapper extends SqlDataMapper implements IBlockDataMapper
{
    /**
     * @param IStringerEntity $entity
     */
    public function add($entity)
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $layoutIdType = PDO::PARAM_NULL;
        if ($entity->getLayoutId()) {
            $layoutIdType = PDO::PARAM_STR;
        }
        $query = (new QueryBuilder())
            ->insert(
                'blocks',
                [
                    'id'         => [$entity->getId(), PDO::PARAM_STR],
                    'identifier' => [$entity->getIdentifier(), PDO::PARAM_STR],
                    'title'      => [$entity->getTitle(), PDO::PARAM_STR],
                    'body'       => [$entity->getBody(), PDO::PARAM_STR],
                    'layout'     => [$entity->getLayout(), PDO::PARAM_STR],
                    'layout_id'  => [$entity->getLayoutId(), $layoutIdType],
                ]
            );

        $statement = $this->writeConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();
    }

    /**
     * @param IStringerEntity $entity
     *
     * @throws \Opulence\QueryBuilders\InvalidQueryException
     */
    public function delete($entity)
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $query = (new QueryBuilder())
            ->update('blocks', 'blocks', ['deleted' => [1, PDO::PARAM_INT]])
            ->where('id = ?')
            ->addUnnamedPlaceholderValue($entity->getId(), PDO::PARAM_STR);

        $statement = $this->writeConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();
    }

    /**
     * @return Entity[]
     * @throws \Opulence\Orm\OrmException
     */
    public function getAll(): array
    {
        $query = $this->getBaseQuery();

        return $this->read($query->getSql(), [], self::VALUE_TYPE_ARRAY);
    }

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
        $query = $this->getBaseQuery()
            ->limit($pageSize)
            ->offset($limitFrom);

        foreach ($orders as $order) {
            $query->addOrderBy($order);
        }

        foreach ($conditions as $condition) {
            $query->andWhere($condition);
        }

        $replaceCount = 1;

        $sql = $query->getSql();
        $sql = str_replace('SELECT', 'SELECT SQL_CALC_FOUND_ROWS', $sql, $replaceCount);

        return $this->read($sql, $params, self::VALUE_TYPE_ARRAY);
    }

    /**
     * @param int|string $id
     *
     * @return Entity|null
     * @throws \Opulence\Orm\OrmException
     */
    public function getById($id)
    {
        $query = $this->getBaseQuery()->andWhere('blocks.id = :block_id');

        $parameters = [
            'block_id' => [$id, PDO::PARAM_STR],
        ];

        $sql = $query->getSql();

        return $this->read($sql, $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string $identifier
     *
     * @return Entity|null
     * @throws \Opulence\Orm\OrmException
     */
    public function getByIdentifier(string $identifier): ?Entity
    {
        $query = $this->getBaseQuery()->andWhere('blocks.identifier = :identifier');

        $parameters = [
            'identifier' => [$identifier, PDO::PARAM_STR],
        ];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string[] $identifiers
     *
     * @return Entity[]
     * @throws \Opulence\Orm\OrmException
     */
    public function getWithLayoutByIdentifiers(array $identifiers): array
    {
        if (count($identifiers) === 0) {
            return [];
        }

        $conditions = new ConditionFactory();
        $query      = $this->getWithLayoutQuery()->andWhere($conditions->in('blocks.identifier', $identifiers));

        return $this->read($query->getSql(), $query->getParameters(), self::VALUE_TYPE_ARRAY);
    }

    /**
     * @param IStringerEntity $entity
     *
     * @throws \Opulence\QueryBuilders\InvalidQueryException
     */
    public function update($entity)
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $layoutIdType = $entity->getLayoutId() ? PDO::PARAM_STR : PDO::PARAM_NULL;

        $query = (new QueryBuilder())
            ->update(
                'blocks',
                'blocks',
                [
                    'identifier' => [$entity->getIdentifier(), PDO::PARAM_STR],
                    'title'      => [$entity->getTitle(), PDO::PARAM_STR],
                    'body'       => [$entity->getBody(), PDO::PARAM_STR],
                    'layout'     => [$entity->getLayout(), PDO::PARAM_STR],
                    'layout_id'  => [$entity->getLayoutId(), $layoutIdType],
                ]
            )
            ->where('id = ?')
            ->andWhere('deleted_at IS NULL')
            ->addUnnamedPlaceholderValue($entity->getId(), PDO::PARAM_STR);

        $statement = $this->writeConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();
    }

    /**
     * @param array $hash
     *
     * @return Entity
     */
    protected function loadEntity(array $hash)
    {
        $layoutId = $hash['layout_id'] ?: null;

        return new Entity(
            $hash['id'],
            $hash['identifier'],
            $hash['title'],
            $hash['body'],
            $hash['layout'],
            $layoutId
        );
    }

    /**
     * @return SelectQuery
     */
    private function getBaseQuery(): SelectQuery
    {
        /** @var SelectQuery $query */
        $query = (new QueryBuilder())
            ->select(
                'blocks.id',
                'blocks.identifier',
                'blocks.title',
                'blocks.body',
                'blocks.layout_id',
                'blocks.layout'
            )
            ->from('blocks')
            ->where('blocks.deleted_at IS NULL');

        return $query;
    }

    /**
     * @return SelectQuery
     */
    private function getWithLayoutQuery(): SelectQuery
    {
        /** @var SelectQuery $query */
        $query = (new QueryBuilder())
            ->select(
                'blocks.id',
                'blocks.identifier',
                'blocks.title',
                'blocks.body',
                'blocks.layout_id',
                'COALESCE(layouts.body, blocks.layout) AS layout'
            )
            ->from('blocks')
            ->leftJoin('block_layouts', 'layouts', 'layouts.id = blocks.layout_id')
            ->where('blocks.deleted_at IS NULL')
            ->andWhere('layouts.deleted_at IS NULL OR layouts.deleted IS NULL');

        return $query;
    }
}
