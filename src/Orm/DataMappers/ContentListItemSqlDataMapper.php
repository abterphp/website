<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMappers;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Website\Domain\Entities\ContentListItem as Entity;
use Opulence\Orm\DataMappers\SqlDataMapper;
use Opulence\QueryBuilders\Conditions\ConditionFactory;
use Opulence\QueryBuilders\Expression;
use Opulence\QueryBuilders\MySql\QueryBuilder;
use Opulence\QueryBuilders\MySql\SelectQuery;
use PDO;

/**
 * @phan-file-suppress PhanTypeMismatchArgument
 */
class ContentListItemSqlDataMapper extends SqlDataMapper implements IContentListItemDataMapper
{
    /**
     * @param IStringerEntity $entity
     */
    public function add($entity)
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $query = (new QueryBuilder())
            ->insert(
                'list_items',
                [
                    'id'        => [$entity->getId(), PDO::PARAM_STR],
                    'list_id'   => [$entity->getListId(), PDO::PARAM_STR],
                    'name'      => [$entity->getName(), PDO::PARAM_STR],
                    'name_href' => [$entity->getNameHref(), PDO::PARAM_STR],
                    'body'      => [$entity->getBody(), PDO::PARAM_STR],
                    'body_href' => [$entity->getBodyHref(), PDO::PARAM_STR],
                    'img_src'   => [$entity->getImgSrc(), PDO::PARAM_STR],
                    'img_alt'   => [$entity->getImgAlt(), PDO::PARAM_STR],
                    'img_href'  => [$entity->getImgHref(), PDO::PARAM_STR],
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
            ->update('list_items', 'list_items', ['deleted_at' => new Expression('NOW()')])
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
        $query = $this->getBaseQuery()->andWhere('list_items.id = :list_item_id');

        $parameters = [
            'list_item_id' => [$id, PDO::PARAM_STR],
        ];

        $sql = $query->getSql();

        return $this->read($sql, $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string $listId
     *
     * @return array
     * @throws \Opulence\Orm\OrmException
     */
    public function getByListId(string $listId): array
    {
        $query = $this->getBaseQuery()->andWhere('list_items.list_id = :list_id');

        $parameters = [
            'list_id' => [$listId, PDO::PARAM_STR],
        ];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ARRAY);
    }

    /**
     * @param string[] $listIds
     *
     * @return Entity[]
     * @throws \Opulence\Orm\OrmException
     */
    public function getByListIds(array $listIds): array
    {
        if (count($listIds) === 0) {
            return [];
        }

        $conditions = new ConditionFactory();
        $query = $this->getBaseQuery()->andWhere($conditions->in('list_items.list_id', $listIds));

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

        $query = (new QueryBuilder())
            ->update(
                'list_items',
                'list_items',
                [
                    'list_id'   => [$entity->getListId(), PDO::PARAM_STR],
                    'name'      => [$entity->getName(), PDO::PARAM_STR],
                    'name_href' => [$entity->getNameHref(), PDO::PARAM_STR],
                    'body'      => [$entity->getBody(), PDO::PARAM_STR],
                    'body_href' => [$entity->getBodyHref(), PDO::PARAM_STR],
                    'img_src'   => [$entity->getImgSrc(), PDO::PARAM_STR],
                    'img_alt'   => [$entity->getImgAlt(), PDO::PARAM_STR],
                    'img_href'  => [$entity->getImgHref(), PDO::PARAM_STR],
                ]
            )
            ->where('id = ?')
            ->andWhere('list_items.deleted_at IS NULL')
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
        return new Entity(
            $hash['id'],
            $hash['list_id'],
            $hash['name'],
            $hash['name_href'],
            $hash['body'],
            $hash['body_href'],
            $hash['img_src'],
            $hash['img_alt'],
            $hash['img_href']
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
                'list_items.id',
                'list_items.list_id',
                'list_items.name',
                'list_items.name_href',
                'list_items.body',
                'list_items.body_href',
                'list_items.img_src',
                'list_items.img_alt',
                'list_items.img_href'
            )
            ->from('list_items')
            ->where('list_items.deleted_at IS NULL');

        return $query;
    }
}
