<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMapper;

use AbterPhp\Framework\Orm\DataMappers\SqlTestCase;
use AbterPhp\Website\Domain\Entities\Page;
use AbterPhp\Website\Orm\DataMappers\PageSqlDataMapper;

class PageSqlDataMapperTest extends SqlTestCase
{
    /** @var PageSqlDataMapper */
    protected $sut;

    public function setUp()
    {
        parent::setUp();

        $this->sut = new PageSqlDataMapper($this->readConnectionMock, $this->writeConnectionMock);
    }

    /**
     * @param string      $id
     * @param string|null $categoryId
     * @param string      $layout
     * @param string|null $layoutId
     *
     * @return Page
     */
    protected function getEntity(
        string $id = '',
        ?string $categoryId = null,
        string $layout = 'qux',
        ?string $layoutId = null
    ): Page {
        $meta   = new Page\Meta('m1', 'm2', 'm3', 'm4', 'm5', 'm6', 'm7', 'm8');
        $assets = new Page\Assets('foo', 'baz', 'yak', ['zar'], ['boi'], null);

        return new Page($id, 'foo', 'bar', 'baz', $categoryId, $layout, $layoutId, $meta, $assets);
    }

    public function testAddSimple()
    {
        $nextId = 'fee8891b-2c31-49db-9a44-3e6179865c1f';

        $entity = $this->getEntity($nextId);
        $meta   = $entity->getMeta();
        $assets = $entity->getAssets();

        $sql    = 'INSERT INTO pages (id, identifier, title, body, category_id, layout, layout_id, meta_description, meta_robots, meta_author, meta_copyright, meta_keywords, meta_og_title, meta_og_image, meta_og_description, header, footer, css_files, js_files) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'; // phpcs:ignore
        $values = [
            [$entity->getId(), \PDO::PARAM_STR],
            [$entity->getIdentifier(), \PDO::PARAM_STR],
            [$entity->getTitle(), \PDO::PARAM_STR],
            [$entity->getBody(), \PDO::PARAM_STR],
            [$entity->getCategoryId(), \PDO::PARAM_NULL],
            [$entity->getLayout(), \PDO::PARAM_STR],
            [$entity->getLayoutId(), \PDO::PARAM_NULL],
            [$meta->getDescription(), \PDO::PARAM_STR],
            [$meta->getRobots(), \PDO::PARAM_STR],
            [$meta->getAuthor(), \PDO::PARAM_STR],
            [$meta->getCopyright(), \PDO::PARAM_STR],
            [$meta->getKeywords(), \PDO::PARAM_STR],
            [$meta->getOGTitle(), \PDO::PARAM_STR],
            [$meta->getOGImage(), \PDO::PARAM_STR],
            [$meta->getOGDescription(), \PDO::PARAM_STR],
            [$assets->getHeader(), \PDO::PARAM_STR],
            [$assets->getFooter(), \PDO::PARAM_STR],
            [implode("\r\n", $assets->getCssFiles()), \PDO::PARAM_STR],
            [implode("\r\n", $assets->getJsFiles()), \PDO::PARAM_STR],
        ];

        $this->prepare($this->writeConnectionMock, $sql, $this->createWriteStatement($values));

        $this->sut->add($entity);

        $this->assertSame($nextId, (string)$entity->getId());
    }

    public function testAddWithLayoutId()
    {
        $nextId   = '9340c9ec-f1cd-4a85-bc71-15c13c31a22e';
        $layoutId = 'f1be9cd6-e7cb-40c1-b584-f265259bd8de';

        $entity = $this->getEntity($nextId, null, '', $layoutId);
        $meta   = $entity->getMeta();
        $assets = $entity->getAssets();

        $sql    = 'INSERT INTO pages (id, identifier, title, body, category_id, layout, layout_id, meta_description, meta_robots, meta_author, meta_copyright, meta_keywords, meta_og_title, meta_og_image, meta_og_description, header, footer, css_files, js_files) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'; // phpcs:ignore
        $values = [
            [$entity->getId(), \PDO::PARAM_STR],
            [$entity->getIdentifier(), \PDO::PARAM_STR],
            [$entity->getTitle(), \PDO::PARAM_STR],
            [$entity->getBody(), \PDO::PARAM_STR],
            [$entity->getCategoryId(), \PDO::PARAM_NULL],
            [$entity->getLayout(), \PDO::PARAM_STR],
            [$entity->getLayoutId(), \PDO::PARAM_STR],
            [$meta->getDescription(), \PDO::PARAM_STR],
            [$meta->getRobots(), \PDO::PARAM_STR],
            [$meta->getAuthor(), \PDO::PARAM_STR],
            [$meta->getCopyright(), \PDO::PARAM_STR],
            [$meta->getKeywords(), \PDO::PARAM_STR],
            [$meta->getOGTitle(), \PDO::PARAM_STR],
            [$meta->getOGImage(), \PDO::PARAM_STR],
            [$meta->getOGDescription(), \PDO::PARAM_STR],
            [$assets->getHeader(), \PDO::PARAM_STR],
            [$assets->getFooter(), \PDO::PARAM_STR],
            [implode("\r\n", $assets->getCssFiles()), \PDO::PARAM_STR],
            [implode("\r\n", $assets->getJsFiles()), \PDO::PARAM_STR],
        ];

        $this->prepare($this->writeConnectionMock, $sql, $this->createWriteStatement($values));

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testAddWithCategoryId()
    {
        $nextId     = '9340c9ec-f1cd-4a85-bc71-15c13c31a22e';
        $categoryId = 'f1be9cd6-e7cb-40c1-b584-f265259bd8de';

        $entity = $this->getEntity($nextId, $categoryId);
        $meta   = $entity->getMeta();
        $assets = $entity->getAssets();

        $sql    = 'INSERT INTO pages (id, identifier, title, body, category_id, layout, layout_id, meta_description, meta_robots, meta_author, meta_copyright, meta_keywords, meta_og_title, meta_og_image, meta_og_description, header, footer, css_files, js_files) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'; // phpcs:ignore
        $values = [
            [$entity->getId(), \PDO::PARAM_STR],
            [$entity->getIdentifier(), \PDO::PARAM_STR],
            [$entity->getTitle(), \PDO::PARAM_STR],
            [$entity->getBody(), \PDO::PARAM_STR],
            [$entity->getCategoryId(), \PDO::PARAM_STR],
            [$entity->getLayout(), \PDO::PARAM_STR],
            [$entity->getLayoutId(), \PDO::PARAM_NULL],
            [$meta->getDescription(), \PDO::PARAM_STR],
            [$meta->getRobots(), \PDO::PARAM_STR],
            [$meta->getAuthor(), \PDO::PARAM_STR],
            [$meta->getCopyright(), \PDO::PARAM_STR],
            [$meta->getKeywords(), \PDO::PARAM_STR],
            [$meta->getOGTitle(), \PDO::PARAM_STR],
            [$meta->getOGImage(), \PDO::PARAM_STR],
            [$meta->getOGDescription(), \PDO::PARAM_STR],
            [$assets->getHeader(), \PDO::PARAM_STR],
            [$assets->getFooter(), \PDO::PARAM_STR],
            [implode("\r\n", $assets->getCssFiles()), \PDO::PARAM_STR],
            [implode("\r\n", $assets->getJsFiles()), \PDO::PARAM_STR],
        ];

        $this->prepare($this->writeConnectionMock, $sql, $this->createWriteStatement($values));

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testDelete()
    {
        $id     = '2fdfb4aa-b199-40d6-86bd-06eed25bff43';
        $entity = $this->getEntity($id);

        $sql    = 'UPDATE pages AS pages SET deleted = ? WHERE (id = ?)'; // phpcs:ignore
        $values = [[1, \PDO::PARAM_INT], [$entity->getId(), \PDO::PARAM_STR]];

        $this->prepare($this->writeConnectionMock, $sql, $this->createWriteStatement($values));

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id       = '63111dd1-4ea8-4152-83fa-59463d1d92fb';
        $entity   = $this->getEntity($id);
        $layoutId = null;

        $sql          = 'SELECT pages.id, pages.identifier, pages.title, pages.category_id, pages.layout_id FROM pages WHERE (pages.deleted = 0)'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'          => $entity->getId(),
                'identifier'  => $entity->getIdentifier(),
                'title'       => $entity->getTitle(),
                'category_id' => $entity->getCategoryId(),
                'layout_id'   => $entity->getLayoutId(),
            ],
        ];

        $this->prepare($this->readConnectionMock, $sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getAll();

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetById()
    {
        $id     = '24ce60d4-95a6-441b-9c95-fe578ef1e23c';
        $entity = $this->getEntity($id);
        $meta   = $entity->getMeta();
        $assets = $entity->getAssets();

        $sql          = 'SELECT pages.id, pages.identifier, pages.title, pages.body, pages.category_id, pages.layout_id, pages.layout, pages.meta_description, pages.meta_robots, pages.meta_author, pages.meta_copyright, pages.meta_keywords, pages.meta_og_title, pages.meta_og_image, pages.meta_og_description, pages.header, pages.footer, pages.css_files, pages.js_files FROM pages WHERE (pages.deleted = 0) AND (pages.id = :page_id)'; // phpcs:ignore
        $values       = ['page_id' => [$id, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'                  => $entity->getId(),
                'identifier'          => $entity->getIdentifier(),
                'title'               => $entity->getTitle(),
                'body'                => $entity->getBody(),
                'category_id'         => $entity->getCategoryId(),
                'layout'              => $entity->getLayout(),
                'layout_id'           => $entity->getLayoutId(),
                'meta_description'    => $meta->getDescription(),
                'meta_robots'         => $meta->getRobots(),
                'meta_author'         => $meta->getAuthor(),
                'meta_copyright'      => $meta->getCopyright(),
                'meta_keywords'       => $meta->getKeywords(),
                'meta_og_title'       => $meta->getOGTitle(),
                'meta_og_image'       => $meta->getOGImage(),
                'meta_og_description' => $meta->getOGDescription(),
                'header'              => $assets->getHeader(),
                'footer'              => $assets->getFooter(),
                'css_files'           => implode("\r\n", $assets->getCssFiles()),
                'js_files'            => implode("\r\n", $assets->getJsFiles()),
            ],
        ];

        $this->prepare($this->readConnectionMock, $sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetByIdentifier()
    {
        $id     = '08cf6a6b-5d86-405b-b573-fa4a6f4c6122';
        $entity = $this->getEntity($id);
        $meta   = $entity->getMeta();
        $assets = $entity->getAssets();

        $sql          = 'SELECT pages.id, pages.identifier, pages.title, pages.body, pages.category_id, pages.layout_id, pages.layout, pages.meta_description, pages.meta_robots, pages.meta_author, pages.meta_copyright, pages.meta_keywords, pages.meta_og_title, pages.meta_og_image, pages.meta_og_description, pages.header, pages.footer, pages.css_files, pages.js_files FROM pages WHERE (pages.deleted = 0) AND (pages.identifier = :identifier)'; // phpcs:ignore
        $values       = ['identifier' => [$entity->getIdentifier(), \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'                  => $entity->getId(),
                'identifier'          => $entity->getIdentifier(),
                'title'               => $entity->getTitle(),
                'body'                => $entity->getBody(),
                'category_id'         => $entity->getCategoryId(),
                'layout'              => $entity->getLayout(),
                'layout_id'           => $entity->getLayoutId(),
                'meta_description'    => $meta->getDescription(),
                'meta_robots'         => $meta->getRobots(),
                'meta_author'         => $meta->getAuthor(),
                'meta_copyright'      => $meta->getCopyright(),
                'meta_keywords'       => $meta->getKeywords(),
                'meta_og_title'       => $meta->getOGTitle(),
                'meta_og_image'       => $meta->getOGImage(),
                'meta_og_description' => $meta->getOGDescription(),
                'header'              => $assets->getHeader(),
                'footer'              => $assets->getFooter(),
                'css_files'           => implode("\r\n", $assets->getCssFiles()),
                'js_files'            => implode("\r\n", $assets->getJsFiles()),
            ],
        ];

        $this->prepare($this->readConnectionMock, $sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getByIdentifier($entity->getIdentifier());

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testUpdateSimple()
    {
        $id     = 'ea075f20-95de-4ce4-9dfb-13bae781031d';
        $entity = $this->getEntity($id);
        $meta   = $entity->getMeta();
        $assets = $entity->getAssets();

        $sql    = 'UPDATE pages AS pages SET identifier = ?, title = ?, body = ?, category_id = ?, layout = ?, layout_id = ?, meta_description = ?, meta_robots = ?, meta_author = ?, meta_copyright = ?, meta_keywords = ?, meta_og_title = ?, meta_og_image = ?, meta_og_description = ?, header = ?, footer = ?, css_files = ?, js_files = ? WHERE (id = ?) AND (deleted = 0)'; // phpcs:ignore
        $values = [
            [$entity->getIdentifier(), \PDO::PARAM_STR],
            [$entity->getTitle(), \PDO::PARAM_STR],
            [$entity->getBody(), \PDO::PARAM_STR],
            [$entity->getCategoryId(), \PDO::PARAM_NULL],
            [$entity->getLayout(), \PDO::PARAM_STR],
            [$entity->getLayoutId(), \PDO::PARAM_NULL],
            [$meta->getDescription(), \PDO::PARAM_STR],
            [$meta->getRobots(), \PDO::PARAM_STR],
            [$meta->getAuthor(), \PDO::PARAM_STR],
            [$meta->getCopyright(), \PDO::PARAM_STR],
            [$meta->getKeywords(), \PDO::PARAM_STR],
            [$meta->getOGTitle(), \PDO::PARAM_STR],
            [$meta->getOGImage(), \PDO::PARAM_STR],
            [$meta->getOGDescription(), \PDO::PARAM_STR],
            [$assets->getHeader(), \PDO::PARAM_STR],
            [$assets->getFooter(), \PDO::PARAM_STR],
            [implode("\r\n", $assets->getCssFiles()), \PDO::PARAM_STR],
            [implode("\r\n", $assets->getJsFiles()), \PDO::PARAM_STR],
            [$entity->getId(), \PDO::PARAM_STR],
        ];

        $this->prepare($this->writeConnectionMock, $sql, $this->createWriteStatement($values));

        $this->sut->update($entity);
    }

    public function testUpdateWithLayoutId()
    {
        $id       = 'd90e6027-75ef-4121-8fda-7f38f7cd39e6';
        $layoutId = 'ffe0bd1c-8b9a-4cb4-8255-86444e37223a';
        $entity   = $this->getEntity($id, null, '', $layoutId);
        $meta     = $entity->getMeta();
        $assets   = $entity->getAssets();

        $sql    = 'UPDATE pages AS pages SET identifier = ?, title = ?, body = ?, category_id = ?, layout = ?, layout_id = ?, meta_description = ?, meta_robots = ?, meta_author = ?, meta_copyright = ?, meta_keywords = ?, meta_og_title = ?, meta_og_image = ?, meta_og_description = ?, header = ?, footer = ?, css_files = ?, js_files = ? WHERE (id = ?) AND (deleted = 0)'; // phpcs:ignore
        $values = [
            [$entity->getIdentifier(), \PDO::PARAM_STR],
            [$entity->getTitle(), \PDO::PARAM_STR],
            [$entity->getBody(), \PDO::PARAM_STR],
            [$entity->getCategoryId(), \PDO::PARAM_NULL],
            [$entity->getLayout(), \PDO::PARAM_STR],
            [$entity->getLayoutId(), \PDO::PARAM_STR],
            [$meta->getDescription(), \PDO::PARAM_STR],
            [$meta->getRobots(), \PDO::PARAM_STR],
            [$meta->getAuthor(), \PDO::PARAM_STR],
            [$meta->getCopyright(), \PDO::PARAM_STR],
            [$meta->getKeywords(), \PDO::PARAM_STR],
            [$meta->getOGTitle(), \PDO::PARAM_STR],
            [$meta->getOGImage(), \PDO::PARAM_STR],
            [$meta->getOGDescription(), \PDO::PARAM_STR],
            [$assets->getHeader(), \PDO::PARAM_STR],
            [$assets->getFooter(), \PDO::PARAM_STR],
            [implode("\r\n", $assets->getCssFiles()), \PDO::PARAM_STR],
            [implode("\r\n", $assets->getJsFiles()), \PDO::PARAM_STR],
            [$entity->getId(), \PDO::PARAM_STR],
        ];

        $this->prepare($this->writeConnectionMock, $sql, $this->createWriteStatement($values));

        $this->sut->update($entity);
    }

    public function testUpdateWithCategoryId()
    {
        $id         = 'd90e6027-75ef-4121-8fda-7f38f7cd39e6';
        $categoryId = 'ffe0bd1c-8b9a-4cb4-8255-86444e37223a';
        $entity     = $this->getEntity($id, $categoryId);
        $meta       = $entity->getMeta();
        $assets     = $entity->getAssets();

        $sql    = 'UPDATE pages AS pages SET identifier = ?, title = ?, body = ?, category_id = ?, layout = ?, layout_id = ?, meta_description = ?, meta_robots = ?, meta_author = ?, meta_copyright = ?, meta_keywords = ?, meta_og_title = ?, meta_og_image = ?, meta_og_description = ?, header = ?, footer = ?, css_files = ?, js_files = ? WHERE (id = ?) AND (deleted = 0)'; // phpcs:ignore
        $values = [
            [$entity->getIdentifier(), \PDO::PARAM_STR],
            [$entity->getTitle(), \PDO::PARAM_STR],
            [$entity->getBody(), \PDO::PARAM_STR],
            [$entity->getCategoryId(), \PDO::PARAM_STR],
            [$entity->getLayout(), \PDO::PARAM_STR],
            [$entity->getLayoutId(), \PDO::PARAM_NULL],
            [$meta->getDescription(), \PDO::PARAM_STR],
            [$meta->getRobots(), \PDO::PARAM_STR],
            [$meta->getAuthor(), \PDO::PARAM_STR],
            [$meta->getCopyright(), \PDO::PARAM_STR],
            [$meta->getKeywords(), \PDO::PARAM_STR],
            [$meta->getOGTitle(), \PDO::PARAM_STR],
            [$meta->getOGImage(), \PDO::PARAM_STR],
            [$meta->getOGDescription(), \PDO::PARAM_STR],
            [$assets->getHeader(), \PDO::PARAM_STR],
            [$assets->getFooter(), \PDO::PARAM_STR],
            [implode("\r\n", $assets->getCssFiles()), \PDO::PARAM_STR],
            [implode("\r\n", $assets->getJsFiles()), \PDO::PARAM_STR],
            [$entity->getId(), \PDO::PARAM_STR],
        ];

        $this->prepare($this->writeConnectionMock, $sql, $this->createWriteStatement($values));

        $this->sut->update($entity);
    }

    /**
     * @param array $expectedData
     * @param Page  $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
        $this->assertInstanceOf(Page::class, $entity);
        $this->assertSame($expectedData['id'], $entity->getId());
        $this->assertSame($expectedData['identifier'], $entity->getIdentifier());
        $this->assertSame($expectedData['title'], $entity->getTitle());
        $this->assertSame($expectedData['category_id'], $entity->getCategoryId());
        $this->assertSame($expectedData['layout_id'], $entity->getLayoutId());

        $this->assertEntityAssets($expectedData, $entity);
    }

    /**
     * @param array $expectedData
     * @param Page  $entity
     */
    protected function assertEntityAssets(array $expectedData, $entity)
    {
        $assets = $entity->getAssets();
        if (!$assets) {
            return;
        }

        $this->assertSame($expectedData['header'], $assets->getHeader());
        $this->assertSame($expectedData['footer'], $assets->getFooter());
        $this->assertSame((array)$expectedData['css_files'], $assets->getCssFiles());
        $this->assertSame((array)$expectedData['js_files'], $assets->getJsFiles());
    }
}
