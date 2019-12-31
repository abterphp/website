<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMappers;

use AbterPhp\Admin\Orm\DataMappers\IdGeneratorUserTrait;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Helper\DateHelper;
use AbterPhp\Website\Domain\Entities\ContentList as Entity;
use AbterPhp\Website\Domain\Entities\ContentListItem;
use Opulence\Orm\DataMappers\SqlDataMapper;
use Opulence\QueryBuilders\MySql\QueryBuilder;
use Opulence\QueryBuilders\MySql\SelectQuery;
use Opulence\QueryBuilders\Query;
use PDO;

/**
 * @phan-file-suppress PhanTypeMismatchArgument
 */
class ContentListSqlDataMapper extends SqlDataMapper implements IContentListDataMapper
{
    use IdGeneratorUserTrait;

    /**
     * @param IStringerEntity $entity
     */
    public function add($entity)
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $query = (new QueryBuilder())
            ->insert(
                'lists',
                [
                    'id'         => [$entity->getId(), PDO::PARAM_STR],
                    'type_id'    => [$entity->getTypeId(), PDO::PARAM_STR],
                    'name'       => [$entity->getName(), PDO::PARAM_STR],
                    'identifier' => [$entity->getIdentifier(), PDO::PARAM_STR],
                    'classes'    => [$entity->getClasses(), PDO::PARAM_STR],
                    'protected'  => [$entity->isProtected(), PDO::PARAM_BOOL],
                    'with_image' => [$entity->isWithImage(), PDO::PARAM_BOOL],
                    'with_links' => [$entity->isWithLinks(), PDO::PARAM_BOOL],
                    'with_html'  => [$entity->isWithHtml(), PDO::PARAM_BOOL],
                ]
            );

        $statement = $this->writeConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();

        $this->upsertItems($entity);
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
            ->update('lists', 'lists', ['deleted_at' => [(new \DateTime())->format(\DATE_RFC3339), \PDO::PARAM_STR]])
            ->where('id = ?')
            ->addUnnamedPlaceholderValue($entity->getId(), PDO::PARAM_STR);

        $statement = $this->writeConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();

        $this->deleteItems($entity);
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
        $query = $this->getBaseQuery()->andWhere('lists.id = :list_id');

        $parameters = [
            'list_id' => [$id, PDO::PARAM_STR],
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
        $query = $this->getBaseQuery()->andWhere('lists.identifier = :identifier');

        $parameters = [
            'identifier' => [$identifier, PDO::PARAM_STR],
        ];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY, true);
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
                'lists',
                'lists',
                [
                    'type_id'    => [$entity->getTypeId(), PDO::PARAM_STR],
                    'name'       => [$entity->getName(), PDO::PARAM_STR],
                    'identifier' => [$entity->getIdentifier(), PDO::PARAM_STR],
                    'classes'    => [$entity->getClasses(), PDO::PARAM_STR],
                    'protected'  => [$entity->isProtected(), PDO::PARAM_BOOL],
                    'with_image' => [$entity->isWithImage(), PDO::PARAM_BOOL],
                    'with_links' => [$entity->isWithLinks(), PDO::PARAM_BOOL],
                    'with_html'  => [$entity->isWithHtml(), PDO::PARAM_BOOL],
                ]
            )
            ->where('id = ?')
            ->andWhere('lists.deleted_at IS NULL')
            ->addUnnamedPlaceholderValue($entity->getId(), PDO::PARAM_STR);

        $statement = $this->writeConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();

        $this->upsertItems($entity);
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
            $hash['type_id'],
            $hash['name'],
            $hash['identifier'],
            $hash['classes'],
            (bool)$hash['protected'],
            (bool)$hash['with_image'],
            (bool)$hash['with_links'],
            (bool)$hash['with_html']
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
                'lists.id',
                'lists.type_id',
                'lists.name',
                'lists.identifier',
                'lists.classes',
                'lists.protected',
                'lists.with_image',
                'lists.with_links',
                'lists.with_html'
            )
            ->from('lists')
            ->where('lists.deleted_at IS NULL');

        return $query;
    }

    /**
     * @param Entity $entity
     */
    protected function upsertItems(Entity $entity)
    {
        foreach ($entity->getItems() as $item) {
            if (!$item->getId() && $item->getDeletedAt()) {
                continue;
            }

            $query = $item->getId() ? $this->createUpdateQuery($item) : $this->createInsertQuery($item);

            $sql = $query->getSql();

            $statement = $this->writeConnection->prepare($sql);
            $statement->bindValues($query->getParameters());
            $statement->execute();
        }
    }

    /**
     * @param ContentListItem $item
     *
     * @return Query
     */
    protected function createUpdateQuery(ContentListItem $item): Query
    {
        $deletedAt = $item->getDeletedAt()
            ? [DateHelper::mysqlDateTime($item->getDeletedAt()), \PDO::PARAM_STR]
            : [null, \PDO::PARAM_STR];

        return (new QueryBuilder())
            ->update(
                'list_items',
                'list_items',
                [
                    'list_id'    => [$item->getListId(), \PDO::PARAM_STR],
                    'name'       => [$item->getName(), \PDO::PARAM_STR],
                    'name_href'  => [$item->getNameHref(), \PDO::PARAM_STR],
                    'body'       => [$item->getBody(), \PDO::PARAM_STR],
                    'body_href'  => [$item->getBodyHref(), \PDO::PARAM_STR],
                    'img_src'    => [$item->getImgSrc(), \PDO::PARAM_STR],
                    'img_alt'    => [$item->getImgAlt(), \PDO::PARAM_STR],
                    'img_href'   => [$item->getImgHref(), \PDO::PARAM_STR],
                    'deleted_at' => $deletedAt,
                ]
            )
            ->where('id = ?')
            ->addUnnamedPlaceholderValue($item->getId(), \PDO::PARAM_STR);
    }

    /**
     * @param ContentListItem $item
     *
     * @return Query
     */
    protected function createInsertQuery(ContentListItem $item): Query
    {
        return (new QueryBuilder())
            ->insert(
                'list_items',
                [
                    'id'        => [$this->getIdGenerator()->generate($item), \PDO::PARAM_STR],
                    'list_id'   => [$item->getListId(), \PDO::PARAM_STR],
                    'name'      => [$item->getName(), \PDO::PARAM_STR],
                    'name_href' => [$item->getNameHref(), \PDO::PARAM_STR],
                    'body'      => [$item->getBody(), \PDO::PARAM_STR],
                    'body_href' => [$item->getBodyHref(), \PDO::PARAM_STR],
                    'img_src'   => [$item->getImgSrc(), \PDO::PARAM_STR],
                    'img_alt'   => [$item->getImgAlt(), \PDO::PARAM_STR],
                    'img_href'  => [$item->getImgHref(), \PDO::PARAM_STR],
                ]
            );
    }

    /**
     * @param Entity $entity
     */
    protected function deleteItems(Entity $entity)
    {
        $query = (new QueryBuilder())
            ->update('list_items', 'list_items', ['deleted_at' => [DateHelper::mysqlDateTime(), \PDO::PARAM_STR]])
            ->where('list_id = ?')
            ->andWhere('deleted_at IS NOT NULL')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_STR);

        $sql = $query->getSql();

        $statement = $this->writeConnection->prepare($sql);
        $statement->bindValues($query->getParameters());
        $statement->execute();
    }
}
