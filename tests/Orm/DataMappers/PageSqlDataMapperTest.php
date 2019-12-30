<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMapper;

use AbterPhp\Admin\TestCase\Orm\DataMapperTestCase;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\TestDouble\Database\MockStatementFactory;
use AbterPhp\Website\Domain\Entities\Page;
use AbterPhp\Website\Domain\Entities\PageCategory;
use AbterPhp\Website\Domain\Entities\PageLayout;
use AbterPhp\Website\Orm\DataMappers\PageSqlDataMapper;
use PHPUnit\Framework\MockObject\MockObject;

class PageSqlDataMapperTest extends DataMapperTestCase
{
    /** @var PageSqlDataMapper - System Under Test */
    protected $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new PageSqlDataMapper($this->readConnectionMock, $this->writeConnectionMock);
    }

    /**
     * @param string          $id
     * @param string|null     $categoryId
     * @param string          $layout
     * @param PageLayout|null $layoutEntity
     * @param bool            $withAssets
     *
     * @return Page
     */
    protected function createEntity(
        string $id = '',
        ?string $categoryId = null,
        string $layout = 'qux',
        ?string $layoutId = null,
        bool $withAssets = true
    ): Page {
        $meta     = new Page\Meta('m1', 'm2', 'm3', 'm4', 'm5', 'm6', 'm7', 'm8');
        $assets   = $withAssets ? new Page\Assets('foo', 'baz', 'yak', ['zar'], ['boi'], null) : null;
        $category = $categoryId ? new PageCategory($categoryId, '', '') : null;

        return new Page($id, 'foo', 'bar', 'baz', 'quix', false, $category, $layout, $layoutId, $meta, $assets);
    }

    public function testAddSimple()
    {
        $nextId = 'fee8891b-2c31-49db-9a44-3e6179865c1f';

        $entity = $this->createEntity($nextId);
        $meta   = $entity->getMeta();
        $assets = $entity->getAssets();

        $sql       = 'INSERT INTO pages (id, identifier, title, lead, body, is_draft, category_id, layout, layout_id, meta_description, meta_robots, meta_author, meta_copyright, meta_keywords, meta_og_title, meta_og_image, meta_og_description, header, footer, css_files, js_files) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'; // phpcs:ignore
        $values    = [
            [$entity->getId(), \PDO::PARAM_STR],
            [$entity->getIdentifier(), \PDO::PARAM_STR],
            [$entity->getTitle(), \PDO::PARAM_STR],
            [$entity->getLead(), \PDO::PARAM_STR],
            [$entity->getBody(), \PDO::PARAM_STR],
            [$entity->isDraft(), \PDO::PARAM_BOOL],
            [null, \PDO::PARAM_NULL],
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
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $this->sut->add($entity);

        $this->assertSame($nextId, (string)$entity->getId());
    }

    public function testAddWithLayoutId()
    {
        $nextId   = '9340c9ec-f1cd-4a85-bc71-15c13c31a22e';
        $layoutId = 'f1be9cd6-e7cb-40c1-b584-f265259bd8de';

        $entity = $this->createEntity($nextId, null, '', $layoutId);
        $meta   = $entity->getMeta();
        $assets = $entity->getAssets();

        $sql       = 'INSERT INTO pages (id, identifier, title, lead, body, is_draft, category_id, layout, layout_id, meta_description, meta_robots, meta_author, meta_copyright, meta_keywords, meta_og_title, meta_og_image, meta_og_description, header, footer, css_files, js_files) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'; // phpcs:ignore
        $values    = [
            [$entity->getId(), \PDO::PARAM_STR],
            [$entity->getIdentifier(), \PDO::PARAM_STR],
            [$entity->getTitle(), \PDO::PARAM_STR],
            [$entity->getLead(), \PDO::PARAM_STR],
            [$entity->getBody(), \PDO::PARAM_STR],
            [$entity->isDraft(), \PDO::PARAM_BOOL],
            [null, \PDO::PARAM_NULL],
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
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testAddWithCategoryId()
    {
        $nextId     = '9340c9ec-f1cd-4a85-bc71-15c13c31a22e';
        $categoryId = 'f1be9cd6-e7cb-40c1-b584-f265259bd8de';

        $entity = $this->createEntity($nextId, $categoryId);
        $meta   = $entity->getMeta();
        $assets = $entity->getAssets();

        $sql       = 'INSERT INTO pages (id, identifier, title, lead, body, is_draft, category_id, layout, layout_id, meta_description, meta_robots, meta_author, meta_copyright, meta_keywords, meta_og_title, meta_og_image, meta_og_description, header, footer, css_files, js_files) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'; // phpcs:ignore
        $values    = [
            [$entity->getId(), \PDO::PARAM_STR],
            [$entity->getIdentifier(), \PDO::PARAM_STR],
            [$entity->getTitle(), \PDO::PARAM_STR],
            [$entity->getLead(), \PDO::PARAM_STR],
            [$entity->getBody(), \PDO::PARAM_STR],
            [$entity->isDraft(), \PDO::PARAM_BOOL],
            [$entity->getCategory()->getId(), \PDO::PARAM_STR],
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
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testDelete()
    {
        $id     = '2fdfb4aa-b199-40d6-86bd-06eed25bff43';
        $entity = $this->createEntity($id);

        $sql       = 'UPDATE pages AS pages SET deleted = ? WHERE (id = ?)'; // phpcs:ignore
        $values    = [[1, \PDO::PARAM_INT], [$entity->getId(), \PDO::PARAM_STR]];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id       = '63111dd1-4ea8-4152-83fa-59463d1d92fb';
        $entity   = $this->createEntity($id);
        $layoutId = null;

        $sql          = 'SELECT pages.id, pages.identifier, pages.title, pages.is_draft, pages.category_id, pages.layout_id FROM pages WHERE (pages.deleted_at IS NULL)'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'          => $entity->getId(),
                'identifier'  => $entity->getIdentifier(),
                'title'       => $entity->getTitle(),
                'is_draft'    => $entity->isDraft(),
                'category_id' => null,
                'layout_id'   => $entity->getLayoutId(),
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getAll();

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetPage()
    {
        $id         = 'df6b4637-634e-4544-a167-2bddf3eab498';
        $identifier = 'foo';
        $title      = 'bar';
        $isDraft    = false;
        $categoryId = '6c79a886-9c74-441b-b205-dc1d274e7e55';
        $layoutId   = '0ec12802-0eb4-4a90-b0ba-454d4d42a367';

        $sql          = 'SELECT SQL_CALC_FOUND_ROWS pages.id, pages.identifier, pages.title, pages.is_draft, categories.name AS category_name FROM pages LEFT JOIN page_categories AS categories ON categories.id = pages.category_id WHERE (pages.deleted_at IS NULL) LIMIT 10 OFFSET 0'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'          => $id,
                'identifier'  => $identifier,
                'title'       => $title,
                'is_draft'    => $isDraft,
                'category_id' => $categoryId,
                'layout_id'   => $layoutId,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getPage(0, 10, [], [], []);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetPageWithOrdersAndConditions()
    {
        $id         = 'df6b4637-634e-4544-a167-2bddf3eab498';
        $identifier = 'foo';
        $title      = 'bar';
        $isDraft    = false;
        $categoryId = '6c79a886-9c74-441b-b205-dc1d274e7e55';
        $layoutId   = '0ec12802-0eb4-4a90-b0ba-454d4d42a367';

        $orders     = ['pages.identifier ASC'];
        $conditions = ['pages.identifier LIKE \'abc%\'', 'pages.identifier LIKE \'%bca\''];

        $sql          = 'SELECT SQL_CALC_FOUND_ROWS pages.id, pages.identifier, pages.title, pages.is_draft, categories.name AS category_name FROM pages LEFT JOIN page_categories AS categories ON categories.id = pages.category_id WHERE (pages.deleted_at IS NULL) AND (pages.identifier LIKE \'abc%\') AND (pages.identifier LIKE \'%bca\') ORDER BY pages.identifier ASC LIMIT 10 OFFSET 0'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'          => $id,
                'identifier'  => $identifier,
                'title'       => $title,
                'is_draft'    => $isDraft,
                'category_id' => $categoryId,
                'layout_id'   => $layoutId,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getPage(0, 10, $orders, $conditions, []);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetByCategoryIdentifiers()
    {
        $id         = 'df6b4637-634e-4544-a167-2bddf3eab498';
        $identifier = 'foo';
        $title      = 'bar';
        $isDraft    = false;
        $categoryId = '6c79a886-9c74-441b-b205-dc1d274e7e55';
        $layoutId   = '0ec12802-0eb4-4a90-b0ba-454d4d42a367';

        $identifiers = [$identifier];

        $sql          = 'SELECT pages.id, pages.identifier, pages.title, pages.lead, pages.is_draft, page_categories.id AS category_id, page_categories.identifier AS category_identifier, page_categories.name AS category_name FROM pages INNER JOIN page_categories AS page_categories ON page_categories.id = pages.category_id WHERE (pages.deleted_at IS NULL) AND (page_categories.identifier IN (?)) AND (pages.is_draft = 0)'; // phpcs:ignore
        $values       = [
            [$identifier, \PDO::PARAM_STR],
        ];
        $expectedData = [
            [
                'id'          => $id,
                'identifier'  => $identifier,
                'title'       => $title,
                'is_draft'    => $isDraft,
                'category_id' => $categoryId,
                'layout_id'   => $layoutId,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getByCategoryIdentifiers($identifiers);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetByCategoryIdentifiersCanReturnEarly()
    {
        $actualResult = $this->sut->getByCategoryIdentifiers([]);

        $this->assertSame([], $actualResult);
    }

    public function testGetById()
    {
        $id     = '24ce60d4-95a6-441b-9c95-fe578ef1e23c';
        $entity = $this->createEntity($id);
        $meta   = $entity->getMeta();
        $assets = $entity->getAssets();

        $sql          = 'SELECT pages.id, pages.identifier, pages.title, pages.lead, pages.body, pages.is_draft, pages.category_id, pages.layout_id, pages.layout, pages.meta_description, pages.meta_robots, pages.meta_author, pages.meta_copyright, pages.meta_keywords, pages.meta_og_title, pages.meta_og_image, pages.meta_og_description, pages.header, pages.footer, pages.css_files, pages.js_files FROM pages WHERE (pages.deleted_at IS NULL) AND (pages.id = :page_id)'; // phpcs:ignore
        $values       = ['page_id' => [$id, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'                  => $entity->getId(),
                'identifier'          => $entity->getIdentifier(),
                'title'               => $entity->getTitle(),
                'lead'                => $entity->getLead(),
                'body'                => $entity->getBody(),
                'is_draft'            => $entity->isDraft(),
                'category_id'         => null,
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
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetByIdentifier()
    {
        $id     = '08cf6a6b-5d86-405b-b573-fa4a6f4c6122';
        $entity = $this->createEntity($id);
        $meta   = $entity->getMeta();
        $assets = $entity->getAssets();

        $sql          = 'SELECT pages.id, pages.identifier, pages.title, pages.lead, pages.body, pages.is_draft, pages.category_id, pages.layout_id, pages.layout, pages.meta_description, pages.meta_robots, pages.meta_author, pages.meta_copyright, pages.meta_keywords, pages.meta_og_title, pages.meta_og_image, pages.meta_og_description, pages.header, pages.footer, pages.css_files, pages.js_files FROM pages WHERE (pages.deleted_at IS NULL) AND (pages.identifier = :identifier)'; // phpcs:ignore
        $values       = ['identifier' => [$entity->getIdentifier(), \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'                  => $entity->getId(),
                'identifier'          => $entity->getIdentifier(),
                'title'               => $entity->getTitle(),
                'lead'                => $entity->getLead(),
                'body'                => $entity->getBody(),
                'is_draft'            => $entity->isDraft(),
                'is_draft'            => (string)$entity->isDraft(),
                'category_id'         => null,
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
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getByIdentifier($entity->getIdentifier());

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetWithLayout()
    {
        $id       = '08cf6a6b-5d86-405b-b573-fa4a6f4c6122';
        $entity   = $this->createEntity($id);
        $meta     = $entity->getMeta();
        $assets   = $entity->getAssets();
        $layoutId = '3f98d8ae-06b3-4fd3-8539-284b377f741b';

        $sql          = 'SELECT pages.id, pages.identifier, pages.title, pages.lead, pages.body, pages.is_draft, pages.category_id, pages.layout_id, COALESCE(layouts.body, pages.layout) AS layout, pages.meta_description, pages.meta_robots, pages.meta_author, pages.meta_copyright, pages.meta_keywords, pages.meta_og_title, pages.meta_og_image, pages.meta_og_description, pages.header AS header, pages.footer AS footer, pages.css_files AS css_files, pages.js_files AS js_files, layouts.identifier AS layout_identifier, layouts.header AS layout_header, layouts.footer AS layout_footer, layouts.css_files AS layout_css_files, layouts.js_files AS layout_js_files FROM pages LEFT JOIN page_layouts AS layouts ON layouts.id = pages.layout_id WHERE (pages.deleted_at IS NULL) AND (layouts.deleted_at IS NULL OR layouts.deleted IS NULL) AND ((pages.identifier = :identifier OR pages.id = :identifier))';  // phpcs:ignore
        $values       = ['identifier' => [$entity->getIdentifier(), \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'                  => $entity->getId(),
                'identifier'          => $entity->getIdentifier(),
                'title'               => $entity->getTitle(),
                'lead'                => $entity->getLead(),
                'body'                => $entity->getBody(),
                'is_draft'            => $entity->isDraft(),
                'is_draft'            => (string)$entity->isDraft(),
                'category_id'         => null,
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
                'header'              => '',
                'footer'              => '',
                'css_files'           => '',
                'js_files'            => '',
                'layout_identifier'   => $layoutId,
                'layout_header'       => $assets->getHeader(),
                'layout_footer'       => $assets->getFooter(),
                'layout_css_files'    => implode("\r\n", $assets->getCssFiles()),
                'layout_js_files'     => implode("\r\n", $assets->getJsFiles()),
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getWithLayout($entity->getIdentifier());

        $this->assertNotEmpty($actualResult->getAssets());
        $this->assertNotEmpty($actualResult->getAssets()->getLayoutAssets());
        $this->assertEquals($assets->getCssFiles(), $actualResult->getAssets()->getLayoutAssets()->getCssFiles());
        $this->assertEquals($assets->getJsFiles(), $actualResult->getAssets()->getLayoutAssets()->getJsFiles());
    }

    public function testUpdateSimple()
    {
        $id     = 'ea075f20-95de-4ce4-9dfb-13bae781031d';
        $entity = $this->createEntity($id);
        $meta   = $entity->getMeta();
        $assets = $entity->getAssets();

        $sql       = 'UPDATE pages AS pages SET identifier = ?, title = ?, lead = ?, body = ?, is_draft = ?, category_id = ?, layout = ?, layout_id = ?, meta_description = ?, meta_robots = ?, meta_author = ?, meta_copyright = ?, meta_keywords = ?, meta_og_title = ?, meta_og_image = ?, meta_og_description = ?, header = ?, footer = ?, css_files = ?, js_files = ? WHERE (id = ?) AND (deleted_at IS NULL)'; // phpcs:ignore
        $values    = [
            [$entity->getIdentifier(), \PDO::PARAM_STR],
            [$entity->getTitle(), \PDO::PARAM_STR],
            [$entity->getLead(), \PDO::PARAM_STR],
            [$entity->getBody(), \PDO::PARAM_STR],
            [$entity->isDraft(), \PDO::PARAM_BOOL],
            [null, \PDO::PARAM_NULL],
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
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $this->sut->update($entity);
    }

    public function testUpdateWithLayoutId()
    {
        $id       = 'd90e6027-75ef-4121-8fda-7f38f7cd39e6';
        $layoutId = 'ffe0bd1c-8b9a-4cb4-8255-86444e37223a';
        $entity   = $this->createEntity($id, null, '', $layoutId);
        $meta     = $entity->getMeta();
        $assets   = $entity->getAssets();

        $sql       = 'UPDATE pages AS pages SET identifier = ?, title = ?, lead = ?, body = ?, is_draft = ?, category_id = ?, layout = ?, layout_id = ?, meta_description = ?, meta_robots = ?, meta_author = ?, meta_copyright = ?, meta_keywords = ?, meta_og_title = ?, meta_og_image = ?, meta_og_description = ?, header = ?, footer = ?, css_files = ?, js_files = ? WHERE (id = ?) AND (deleted_at IS NULL)'; // phpcs:ignore
        $values    = [
            [$entity->getIdentifier(), \PDO::PARAM_STR],
            [$entity->getTitle(), \PDO::PARAM_STR],
            [$entity->getLead(), \PDO::PARAM_STR],
            [$entity->getBody(), \PDO::PARAM_STR],
            [$entity->isDraft(), \PDO::PARAM_BOOL],
            [null, \PDO::PARAM_NULL],
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
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $this->sut->update($entity);
    }

    public function testUpdateWithoutAssets()
    {
        $id       = 'd90e6027-75ef-4121-8fda-7f38f7cd39e6';
        $layoutId = 'ffe0bd1c-8b9a-4cb4-8255-86444e37223a';
        $entity   = $this->createEntity($id, null, '', $layoutId, false);
        $meta     = $entity->getMeta();

        $sql       = 'UPDATE pages AS pages SET identifier = ?, title = ?, lead = ?, body = ?, is_draft = ?, category_id = ?, layout = ?, layout_id = ?, meta_description = ?, meta_robots = ?, meta_author = ?, meta_copyright = ?, meta_keywords = ?, meta_og_title = ?, meta_og_image = ?, meta_og_description = ? WHERE (id = ?) AND (deleted_at IS NULL)'; // phpcs:ignore
        $values    = [
            [$entity->getIdentifier(), \PDO::PARAM_STR],
            [$entity->getTitle(), \PDO::PARAM_STR],
            [$entity->getLead(), \PDO::PARAM_STR],
            [$entity->getBody(), \PDO::PARAM_STR],
            [$entity->isDraft(), \PDO::PARAM_BOOL],
            [null, \PDO::PARAM_NULL],
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
            [$entity->getId(), \PDO::PARAM_STR],
        ];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $this->sut->update($entity);
    }

    public function testUpdateWithCategoryId()
    {
        $id         = 'd90e6027-75ef-4121-8fda-7f38f7cd39e6';
        $categoryId = 'ffe0bd1c-8b9a-4cb4-8255-86444e37223a';
        $entity     = $this->createEntity($id, $categoryId);
        $meta       = $entity->getMeta();
        $assets     = $entity->getAssets();

        $sql       = 'UPDATE pages AS pages SET identifier = ?, title = ?, lead = ?, body = ?, is_draft = ?, category_id = ?, layout = ?, layout_id = ?, meta_description = ?, meta_robots = ?, meta_author = ?, meta_copyright = ?, meta_keywords = ?, meta_og_title = ?, meta_og_image = ?, meta_og_description = ?, header = ?, footer = ?, css_files = ?, js_files = ? WHERE (id = ?) AND (deleted_at IS NULL)'; // phpcs:ignore
        $values    = [
            [$entity->getIdentifier(), \PDO::PARAM_STR],
            [$entity->getTitle(), \PDO::PARAM_STR],
            [$entity->getLead(), \PDO::PARAM_STR],
            [$entity->getBody(), \PDO::PARAM_STR],
            [$entity->isDraft(), \PDO::PARAM_BOOL],
            [$entity->getCategory()->getId(), \PDO::PARAM_STR],
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
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $this->sut->update($entity);
    }

    public function testAddThrowsExceptionIfCalledWithInvalidEntity()
    {
        $this->expectException(\InvalidArgumentException::class);

        /** @var IStringerEntity|MockObject $entity */
        $entity = $this->createMock(IStringerEntity::class);

        $this->sut->add($entity);
    }

    public function testDeleteThrowsExceptionIfCalledWithInvalidEntity()
    {
        $this->expectException(\InvalidArgumentException::class);

        /** @var IStringerEntity|MockObject $entity */
        $entity = $this->createMock(IStringerEntity::class);

        $this->sut->delete($entity);
    }

    public function testUpdateThrowsExceptionIfCalledWithInvalidEntity()
    {
        $this->expectException(\InvalidArgumentException::class);

        /** @var IStringerEntity|MockObject $entity */
        $entity = $this->createMock(IStringerEntity::class);

        $this->sut->update($entity);
    }

    /**
     * @param array $expectedData
     * @param Page  $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
        $categoryId = $entity->getCategory() ? $entity->getCategory()->getId() : null;

        $this->assertInstanceOf(Page::class, $entity);
        $this->assertSame($expectedData['id'], $entity->getId());
        $this->assertSame($expectedData['identifier'], $entity->getIdentifier());
        $this->assertSame($expectedData['title'], $entity->getTitle());
        $this->assertSame($expectedData['category_id'], $categoryId);
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
