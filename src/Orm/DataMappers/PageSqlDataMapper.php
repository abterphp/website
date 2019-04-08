<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMappers;

use AbterPhp\Website\Domain\Entities\Page as Entity;
use AbterPhp\Website\Domain\Entities\Page\Assets as PageAssets;
use AbterPhp\Website\Domain\Entities\PageLayout\Assets as LayoutAssets;
use Opulence\Orm\DataMappers\SqlDataMapper;
use Opulence\QueryBuilders\MySql\QueryBuilder;
use Opulence\QueryBuilders\MySql\SelectQuery;

class PageSqlDataMapper extends SqlDataMapper implements IPageDataMapper
{
    /**
     * @param Entity $entity
     */
    public function add($entity)
    {
        if (!($entity instanceof Entity)) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Page entity.');
        }

        $data  = $this->getColumnNamesToValues($entity, true);
        $query = (new QueryBuilder())->insert('pages', $data);

        $sql    = $query->getSql();
        $params = $query->getParameters();

        $statement = $this->writeConnection->prepare($sql);
        $statement->bindValues($params);
        $statement->execute();
    }

    /**
     * @param Entity $entity
     */
    public function delete($entity)
    {
        if (!($entity instanceof Entity)) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Page entity.');
        }

        $query = (new QueryBuilder())
            ->update('pages', 'pages', ['deleted' => [1, \PDO::PARAM_INT]])
            ->where('id = ?')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_STR);

        $sql    = $query->getSql();
        $params = $query->getParameters();

        $statement = $this->writeConnection->prepare($sql);
        $statement->bindValues($params);
        $statement->execute();
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $query = $this->getBaseQuery();

        $sql = $query->getSql();

        return $this->read($sql, [], self::VALUE_TYPE_ARRAY);
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
     */
    public function getById($id)
    {
        $query = $this->getExtendedQuery()->andWhere('pages.id = :page_id');

        $sql    = $query->getSql();
        $params = [
            'page_id' => [$id, \PDO::PARAM_STR],
        ];

        return $this->read($sql, $params, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string $title
     *
     * @return ?Entity
     */
    public function getByIdentifier(string $identifier): ?Entity
    {
        $query = $this->getExtendedQuery()->andWhere('pages.identifier = :identifier');

        $sql    = $query->getSql();
        $params = [
            'identifier' => [$identifier, \PDO::PARAM_STR],
        ];

        return $this->read($sql, $params, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string $title
     *
     * @return ?Entity
     */
    public function getWithLayout(string $identifier): ?Entity
    {
        $query = $this->getWithLayoutQuery()->andWhere('pages.identifier = :identifier');

        $sql    = $query->getSql();
        $params = [
            'identifier' => $identifier,
        ];

        return $this->read($sql, $params, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param Entity $entity
     */
    public function update($entity)
    {
        if (!($entity instanceof Entity)) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Page entity.');
        }

        $columnNamesToValues = $this->getColumnNamesToValues($entity, false);

        $query = (new QueryBuilder())
            ->update('pages', 'pages', $columnNamesToValues)
            ->where('id = ?')
            ->andWhere('deleted = 0')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_STR);

        $sql    = $query->getSql();
        $params = $query->getParameters();

        $statement = $this->writeConnection->prepare($sql);
        $statement->bindValues($params);
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
        $layoutIdType = \PDO::PARAM_NULL;
        if ($entity->getLayoutId()) {
            $layoutIdType = \PDO::PARAM_STR;
        }

        $columnNamesToValues = [
            'identifier' => [$entity->getIdentifier(), \PDO::PARAM_STR],
            'title'      => [$entity->getTitle(), \PDO::PARAM_STR],
            'body'       => [$entity->getBody(), \PDO::PARAM_STR],
            'layout'     => [$entity->getLayout(), \PDO::PARAM_STR],
            'layout_id'  => [$entity->getLayoutId(), $layoutIdType],
        ];

        if ($create) {
            $columnNamesToValues = array_merge(['id' => [$entity->getId(), \PDO::PARAM_STR]], $columnNamesToValues);
        }

        $columnNamesToValues = $this->populateWithMeta($entity, $columnNamesToValues);
        $columnNamesToValues = $this->populateWithAssets($entity, $columnNamesToValues);

        return $columnNamesToValues;
    }

    /**
     * @param Entity $entity
     * @param array  $columnNamesToValues
     *
     * @return array
     */
    protected function populateWithMeta(Entity $entity, array $columnNamesToValues): array
    {
        if (!$entity->getMeta()) {
            return $columnNamesToValues;
        }

        $meta = $entity->getMeta();

        $metaValues = [
            'meta_description'    => $meta->getDescription(),
            'meta_robots'         => $meta->getRobots(),
            'meta_author'         => $meta->getAuthor(),
            'meta_copyright'      => $meta->getCopyright(),
            'meta_keywords'       => $meta->getKeywords(),
            'meta_og_title'       => $meta->getOGTitle(),
            'meta_og_image'       => $meta->getOGImage(),
            'meta_og_description' => $meta->getOGDescription(),
        ];

        return array_merge($columnNamesToValues, $metaValues);
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
        $meta     = $this->loadMeta($hash);
        $assets   = $this->loadAssets($hash);
        $body     = empty($hash['body']) ? '' : $hash['body'];
        $layout   = empty($hash['layout']) ? '' : $hash['layout'];
        $layoutId = empty($hash['layout_id']) ? null : $hash['layout_id'];

        return new Entity(
            $hash['id'],
            $hash['identifier'],
            $hash['title'],
            $body,
            $layout,
            $layoutId,
            $meta,
            $assets
        );
    }

    /**
     * @param array $hash
     *
     * @return Entity\Meta|null
     */
    protected function loadMeta(array $hash): ?Entity\Meta
    {
        if (!array_key_exists('meta_description', $hash)) {
            return null;
        }

        return new Entity\Meta(
            $hash['meta_description'],
            $hash['meta_robots'],
            $hash['meta_author'],
            $hash['meta_copyright'],
            $hash['meta_keywords'],
            $hash['meta_og_title'],
            $hash['meta_og_image'],
            $hash['meta_og_description']
        );
    }

    /**
     * @param array $hash
     *
     * @return PageAssets|null
     */
    protected function loadAssets(array $hash): ?PageAssets
    {
        if (!array_key_exists('css_files', $hash)) {
            return null;
        }

        $layoutAssets = $this->loadLayoutAssets($hash);

        return new PageAssets(
            $hash['identifier'],
            $hash['header'],
            $hash['footer'],
            $this->extractFiles($hash['css_files']),
            $this->extractFiles($hash['js_files']),
            $layoutAssets
        );
    }

    /**
     * @param array $hash
     *
     * @return LayoutAssets|null
     */
    protected function loadLayoutAssets(array $hash): ?LayoutAssets
    {
        if (!array_key_exists('layout_css_files', $hash) || null === $hash['layout_css_files']) {
            return null;
        }

        return new LayoutAssets(
            $hash['layout_identifier'],
            $hash['layout_header'],
            $hash['layout_footer'],
            $this->extractFiles($hash['layout_css_files']),
            $this->extractFiles($hash['layout_js_files'])
        );
    }

    /**
     * @param string $rawData
     *
     * @return string[]
     */
    private function extractFiles(string $rawData): array
    {
        if (empty($rawData)) {
            return [];
        }

        return explode("\n", str_replace("\r", "", trim($rawData)));
    }

    /**
     * @return SelectQuery
     */
    private function getBaseQuery(): SelectQuery
    {
        /** @var SelectQuery $query */
        $query = (new QueryBuilder())
            ->select(
                'pages.id',
                'pages.identifier',
                'pages.title',
                'pages.layout_id'
            )
            ->from('pages')
            ->where('pages.deleted = 0');

        return $query;
    }

    /**
     * @return SelectQuery
     */
    private function getExtendedQuery(): SelectQuery
    {
        /** @var SelectQuery $query */
        $query = (new QueryBuilder())
            ->select(
                'pages.id',
                'pages.identifier',
                'pages.title',
                'pages.body',
                'pages.layout_id',
                'pages.layout',
                'pages.meta_description',
                'pages.meta_robots',
                'pages.meta_author',
                'pages.meta_copyright',
                'pages.meta_keywords',
                'pages.meta_og_title',
                'pages.meta_og_image',
                'pages.meta_og_description',
                'pages.header',
                'pages.footer',
                'pages.css_files',
                'pages.js_files'
            )
            ->from('pages')
            ->where('pages.deleted = 0');

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
                'pages.id',
                'pages.identifier',
                'pages.title',
                'pages.body',
                'pages.layout_id',
                'COALESCE(layouts.body, pages.layout) AS layout',
                'pages.meta_description',
                'pages.meta_robots',
                'pages.meta_author',
                'pages.meta_copyright',
                'pages.meta_keywords',
                'pages.meta_og_title',
                'pages.meta_og_image',
                'pages.meta_og_description',
                'pages.header AS header',
                'pages.footer AS footer',
                'pages.css_files AS css_files',
                'pages.js_files AS js_files',
                'layouts.identifier AS layout_identifier',
                'layouts.header AS layout_header',
                'layouts.footer AS layout_footer',
                'layouts.css_files AS layout_css_files',
                'layouts.js_files AS layout_js_files'
            )
            ->from('pages')
            ->leftJoin('page_layouts', 'layouts', 'layouts.id = pages.layout_id')
            ->where('pages.deleted = 0')
            ->andWhere('layouts.deleted = 0 OR layouts.deleted IS NULL');

        return $query;
    }
}
