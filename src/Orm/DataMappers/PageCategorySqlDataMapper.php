<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMappers;

use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Admin\Orm\DataMappers\IdGeneratorUserTrait;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Website\Domain\Entities\PageCategory as Entity;
use Opulence\Orm\DataMappers\SqlDataMapper;
use Opulence\QueryBuilders\MySql\QueryBuilder;
use Opulence\QueryBuilders\MySql\SelectQuery;

/**
 * @phan-file-suppress PhanTypeMismatchArgument
 */
class PageCategorySqlDataMapper extends SqlDataMapper implements IPageCategoryDataMapper
{
    use IdGeneratorUserTrait;

    const USER_GROUP_IDS = 'user_group_ids';

    /**
     * @param IStringerEntity $entity
     */
    public function add($entity)
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $data  = $this->getColumnNamesToValues($entity, true);
        $query = (new QueryBuilder())->insert('page_categories', $data);

        $sql    = $query->getSql();
        $params = $query->getParameters();

        $statement = $this->writeConnection->prepare($sql);
        $statement->bindValues($params);
        $statement->execute();

        $this->addUserGroups($entity);
    }

    /**
     * @param IStringerEntity $entity
     */
    public function delete($entity)
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $query = (new QueryBuilder())
            ->update('page_categories', 'page_categories', ['deleted' => [1, \PDO::PARAM_INT]])
            ->where('id = ?')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_STR);

        $sql    = $query->getSql();
        $params = $query->getParameters();

        $statement = $this->writeConnection->prepare($sql);
        $statement->bindValues($params);
        $statement->execute();

        $this->deleteUserGroups($entity);
    }

    /**
     * @return Entity[]
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
     */
    public function getById($id)
    {
        $query = $this->getBaseQuery()->andWhere('pc.id = :category_id');

        $sql    = $query->getSql();
        $params = [
            'category_id' => [$id, \PDO::PARAM_STR],
        ];


        return $this->read($sql, $params, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string $identifier
     *
     * @return Entity|null
     * @throws \Opulence\Orm\OrmException
     */
    public function getByIdentifier(string $identifier): ?Entity
    {
        $query = $this->getBaseQuery()->andWhere('identifier = :identifier');

        $sql    = $query->getSql();
        $params = [
            'identifier' => $identifier,
        ];

        return $this->read($sql, $params, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param IStringerEntity $entity
     */
    public function update($entity)
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $columnNamesToValues = $this->getColumnNamesToValues($entity, false);

        $query = (new QueryBuilder())
            ->update('page_categories', 'page_categories', $columnNamesToValues)
            ->where('id = ?')
            ->andWhere('deleted = 0')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_STR);

        $sql    = $query->getSql();
        $params = $query->getParameters();

        $statement = $this->writeConnection->prepare($sql);
        $statement->bindValues($params);
        $statement->execute();

        $this->deleteUserGroups($entity);
        $this->addUserGroups($entity);
    }

    /**
     * @param Entity $entity
     * @param bool   $create
     *
     * @return array
     */
    protected function getColumnNamesToValues(Entity $entity, bool $create): array
    {
        $columnNamesToValues = [
            'name'       => [$entity->getName(), \PDO::PARAM_STR],
            'identifier' => [$entity->getIdentifier(), \PDO::PARAM_STR],
        ];

        if ($create) {
            $columnNamesToValues = array_merge(['id' => [$entity->getId(), \PDO::PARAM_STR]], $columnNamesToValues);
        }

        return $columnNamesToValues;
    }

    /**
     * @param Entity $entity
     */
    protected function deleteUserGroups(Entity $entity)
    {
        $query = (new QueryBuilder())
            ->delete('user_groups_page_categories')
            ->where('page_category_id = ?')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_STR);

        $sql = $query->getSql();

        $statement = $this->writeConnection->prepare($sql);
        $statement->bindValues($query->getParameters());
        $statement->execute();
    }

    /**
     * @param Entity $entity
     */
    protected function addUserGroups(Entity $entity)
    {
        $idGenerator = $this->getIdGenerator();

        foreach ($entity->getUserGroups() as $userGroup) {
            $query = (new QueryBuilder())
                ->insert(
                    'user_groups_page_categories',
                    [
                        'id'               => [$idGenerator->generate($userGroup), \PDO::PARAM_STR],
                        'user_group_id'    => [$userGroup->getId(), \PDO::PARAM_STR],
                        'page_category_id' => [$entity->getId(), \PDO::PARAM_STR],
                    ]
                );

            $sql = $query->getSql();

            $statement = $this->writeConnection->prepare($sql);
            $statement->bindValues($query->getParameters());
            $statement->execute();
        }
    }

    /**
     * @param array $hash
     *
     * @return Entity
     */
    protected function loadEntity(array $hash)
    {
        $userGroups = $this->loadUserGroups($hash);

        return new Entity(
            $hash['id'],
            $hash['name'],
            $hash['identifier'],
            $userGroups
        );
    }

    /**
     * @param array $hash
     *
     * @return array
     */
    private function loadUserGroups(array $hash): array
    {
        if (empty($hash[static::USER_GROUP_IDS])) {
            return [];
        }

        $userGroups = [];
        foreach (explode(',', $hash[static::USER_GROUP_IDS]) as $id) {
            $userGroups[] = new UserGroup((string)$id, '', '');
        }

        return $userGroups;
    }

    /**
     * @return SelectQuery
     */
    private function getBaseQuery()
    {
        /** @var SelectQuery $query */
        $query = (new QueryBuilder())
            ->select(
                'pc.id',
                'pc.name',
                'pc.identifier',
                'GROUP_CONCAT(ugpc.user_group_id) AS user_group_ids'
            )
            ->from('page_categories', 'pc')
            ->leftJoin('user_groups_page_categories', 'ugpc', 'ugpc.page_category_id = pc.id')
            ->where('pc.deleted = 0')
            ->groupBy('pc.id');

        return $query;
    }
}
