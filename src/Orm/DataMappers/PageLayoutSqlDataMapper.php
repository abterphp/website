<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMappers;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Website\Domain\Entities\PageLayout as Entity;
use Opulence\Orm\DataMappers\SqlDataMapper;
use Opulence\QueryBuilders\MySql\QueryBuilder;
use Opulence\QueryBuilders\MySql\SelectQuery;

/** @phan-file-suppress PhanTypeMismatchArgument */

class PageLayoutSqlDataMapper extends SqlDataMapper implements IPageLayoutDataMapper
{
    /**
     * @param IStringerEntity $entity
     */
    public function add($entity)
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $data  = $this->getColumnNamesToValues($entity, true);
        $query = (new QueryBuilder())->insert('page_layouts', $data);

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
            ->update('page_layouts', 'page_layouts', ['deleted' => [1, \PDO::PARAM_INT]])
            ->where('id = ?')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_STR);

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
        $query = $this->getBaseQuery()->andWhere('page_layouts.id = :layout_id');

        $parameters = [
            'layout_id' => [$id, \PDO::PARAM_STR],
        ];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY, true);
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

        $parameters = [
            'identifier' => $identifier,
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

        $columnNamesToValues = $this->getColumnNamesToValues($entity, false);

        $query = (new QueryBuilder())
            ->update('page_layouts', 'page_layouts', $columnNamesToValues)
            ->where('id = ?')
            ->andWhere('deleted = 0')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_STR);

        $statement = $this->writeConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();
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
            'identifier' => [$entity->getIdentifier(), \PDO::PARAM_STR],
            'body'       => [$entity->getBody(), \PDO::PARAM_STR],
        ];

        if ($create) {
            $columnNamesToValues = array_merge(['id' => [$entity->getId(), \PDO::PARAM_STR]], $columnNamesToValues);
        }

        $columnNamesToValues = $this->populateWithAssets($entity, $columnNamesToValues);

        return $columnNamesToValues;
    }

    /**
     * @param Entity $entity
     * @param array  $columnNamesToValues
     *
     * @return array
     */
    protected function populateWithAssets(Entity $entity, array $columnNamesToValues): array
    {
        if (!$entity->getAssets()) {
            return $columnNamesToValues;
        }

        $assets = $entity->getAssets();

        $assetValues = [
            'header'    => $assets->getHeader(),
            'footer'    => $assets->getFooter(),
            'css_files' => implode("\n\r", $assets->getCssFiles()),
            'js_files'  => implode("\n\r", $assets->getJsFiles()),
        ];

        return array_merge($columnNamesToValues, $assetValues);
    }

    /**
     * @param array $hash
     *
     * @return Entity
     */
    protected function loadEntity(array $hash)
    {
        $assets = $this->loadAssets($hash);

        return new Entity(
            $hash['id'],
            $hash['identifier'],
            $hash['body'],
            $assets
        );
    }

    /**
     * @param array $hash
     *
     * @return Entity\Assets|null
     */
    protected function loadAssets(array $hash): ?Entity\Assets
    {
        return new Entity\Assets(
            $hash['identifier'],
            $hash['header'],
            $hash['footer'],
            explode("\r\n", $hash['css_files']),
            explode("\r\n", $hash['js_files'])
        );
    }

    /**
     * @return SelectQuery
     */
    private function getBaseQuery()
    {
        /** @var SelectQuery $query */
        $query = (new QueryBuilder())
            ->select(
                'page_layouts.id',
                'page_layouts.identifier',
                'page_layouts.body',
                'page_layouts.header',
                'page_layouts.footer',
                'page_layouts.css_files',
                'page_layouts.js_files'
            )
            ->from('page_layouts')
            ->where('page_layouts.deleted = 0');

        return $query;
    }
}
