<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMapper;

use AbterPhp\Admin\TestCase\Orm\DataMapperTestCase;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\TestDouble\Database\MockStatementFactory;
use AbterPhp\Website\Domain\Entities\PageLayout;
use AbterPhp\Website\Orm\DataMappers\PageLayoutSqlDataMapper;
use PHPUnit\Framework\MockObject\MockObject;

class PageLayoutSqlDataMapperTest extends DataMapperTestCase
{
    /** @var PageLayoutSqlDataMapper - System Under Test */
    protected $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new PageLayoutSqlDataMapper($this->readConnectionMock, $this->writeConnectionMock);
    }

    public function testAdd()
    {
        $nextId     = 'c840500d-bd00-410a-912e-e923b8e965e3';
        $name       = 'Foo';
        $identifier = 'foo';
        $body       = 'bar';

        $sql       = 'INSERT INTO page_layouts (id, name, identifier, body) VALUES (?, ?, ?, ?)'; // phpcs:ignore
        $values    = [
            [$nextId, \PDO::PARAM_STR],
            [$name, \PDO::PARAM_STR],
            [$identifier, \PDO::PARAM_STR],
            [$body, \PDO::PARAM_STR],
        ];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $entity = new PageLayout($nextId, $name, $identifier, $body, null);

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testDelete()
    {
        $id         = '1dab2760-9aaa-4f36-a303-42b12e65d165';
        $name       = 'Foo';
        $identifier = 'foo';
        $body       = 'bar';

        $sql       = 'UPDATE page_layouts AS page_layouts SET deleted_at = NOW() WHERE (id = ?)'; // phpcs:ignore
        $values    = [[$id, \PDO::PARAM_STR]];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $entity = new PageLayout($id, $name, $identifier, $body, null);

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id         = 'df6b4637-634e-4544-a167-2bddf3eab498';
        $name       = 'Foo';
        $identifier = 'foo';
        $body       = 'bar';
        $header     = 'baz';
        $footer     = 'yak';
        $cssFiles   = 'zar';
        $jsFiles    = 'boi';

        $sql          = 'SELECT page_layouts.id, page_layouts.name, page_layouts.identifier, page_layouts.body, page_layouts.header, page_layouts.footer, page_layouts.css_files, page_layouts.js_files FROM page_layouts WHERE (page_layouts.deleted_at IS NULL)'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'         => $id,
                'name'       => $name,
                'identifier' => $identifier,
                'body'       => $body,
                'header'     => $header,
                'footer'     => $footer,
                'css_files'  => $cssFiles,
                'js_files'   => $jsFiles,
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
        $name       = 'Foo';
        $identifier = 'foo';
        $body       = 'bar';
        $header     = 'baz';
        $footer     = 'yak';
        $cssFiles   = 'zar';
        $jsFiles    = 'boi';

        $sql          = 'SELECT SQL_CALC_FOUND_ROWS page_layouts.id, page_layouts.name, page_layouts.identifier, page_layouts.body, page_layouts.header, page_layouts.footer, page_layouts.css_files, page_layouts.js_files FROM page_layouts WHERE (page_layouts.deleted_at IS NULL) LIMIT 10 OFFSET 0'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'         => $id,
                'name'       => $name,
                'identifier' => $identifier,
                'body'       => $body,
                'header'     => $header,
                'footer'     => $footer,
                'css_files'  => $cssFiles,
                'js_files'   => $jsFiles,
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
        $name       = 'Foo';
        $identifier = 'foo';
        $body       = 'bar';
        $header     = 'baz';
        $footer     = 'yak';
        $cssFiles   = 'zar';
        $jsFiles    = 'boi';

        $orders     = ['page_layouts.identifier ASC'];
        $conditions = ['page_layouts.identifier LIKE \'abc%\'', 'page_layouts.identifier LIKE \'%bca\''];

        $sql          = 'SELECT SQL_CALC_FOUND_ROWS page_layouts.id, page_layouts.name, page_layouts.identifier, page_layouts.body, page_layouts.header, page_layouts.footer, page_layouts.css_files, page_layouts.js_files FROM page_layouts WHERE (page_layouts.deleted_at IS NULL) AND (page_layouts.identifier LIKE \'abc%\') AND (page_layouts.identifier LIKE \'%bca\') ORDER BY page_layouts.identifier ASC LIMIT 10 OFFSET 0'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'         => $id,
                'name'       => $name,
                'identifier' => $identifier,
                'body'       => $body,
                'header'     => $header,
                'footer'     => $footer,
                'css_files'  => $cssFiles,
                'js_files'   => $jsFiles,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getPage(0, 10, $orders, $conditions, []);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetById()
    {
        $id         = 'fc2bdb23-bdd1-49aa-8613-b5e0ce76450d';
        $name       = 'Foo';
        $identifier = 'foo';
        $body       = 'bar';
        $header     = 'baz';
        $footer     = 'yak';
        $cssFiles   = 'zar';
        $jsFiles    = 'boi';

        $sql          = 'SELECT page_layouts.id, page_layouts.name, page_layouts.identifier, page_layouts.body, page_layouts.header, page_layouts.footer, page_layouts.css_files, page_layouts.js_files FROM page_layouts WHERE (page_layouts.deleted_at IS NULL) AND (page_layouts.id = :layout_id)'; // phpcs:ignore
        $values       = ['layout_id' => [$id, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'         => $id,
                'name'       => $name,
                'identifier' => $identifier,
                'body'       => $body,
                'header'     => $header,
                'footer'     => $footer,
                'css_files'  => $cssFiles,
                'js_files'   => $jsFiles,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetByIdentifier()
    {
        $id         = '4a7847c6-d202-4fc7-972b-bd4d634efda1';
        $name       = 'Foo';
        $identifier = 'foo';
        $body       = 'bar';
        $header     = 'baz';
        $footer     = 'yak';
        $cssFiles   = 'zar';
        $jsFiles    = 'boi';

        $sql          = 'SELECT page_layouts.id, page_layouts.name, page_layouts.identifier, page_layouts.body, page_layouts.header, page_layouts.footer, page_layouts.css_files, page_layouts.js_files FROM page_layouts WHERE (page_layouts.deleted_at IS NULL) AND (identifier = :identifier)'; // phpcs:ignore
        $values       = ['identifier' => $identifier];
        $expectedData = [
            [
                'id'         => $id,
                'name'       => $name,
                'identifier' => $identifier,
                'body'       => $body,
                'header'     => $header,
                'footer'     => $footer,
                'css_files'  => $cssFiles,
                'js_files'   => $jsFiles,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getByIdentifier($identifier);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testUpdate()
    {
        $id         = 'e62dd68b-c72c-464e-8e03-6ee86f26f592';
        $name       = 'Foo';
        $identifier = 'foo';
        $body       = 'bar';
        $header     = 'baz';
        $footer     = 'yak';
        $cssFiles   = 'zar';
        $jsFiles    = 'boi';

        $sql       = 'UPDATE page_layouts AS page_layouts SET name = ?, identifier = ?, body = ?, header = ?, footer = ?, css_files = ?, js_files = ? WHERE (id = ?) AND (deleted_at IS NULL)'; // phpcs:ignore
        $values    = [
            [$name, \PDO::PARAM_STR],
            [$identifier, \PDO::PARAM_STR],
            [$body, \PDO::PARAM_STR],
            [$header, \PDO::PARAM_STR],
            [$footer, \PDO::PARAM_STR],
            [$cssFiles, \PDO::PARAM_STR],
            [$jsFiles, \PDO::PARAM_STR],
            [$id, \PDO::PARAM_STR],
        ];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $assets = new PageLayout\Assets($identifier, $header, $footer, (array)$cssFiles, (array)$jsFiles);
        $entity = new PageLayout($id, $name, $identifier, $body, $assets);

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
     * @param array      $expectedData
     * @param PageLayout $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
        $this->assertInstanceOf(PageLayout::class, $entity);
        $this->assertSame($expectedData['id'], $entity->getId());
        $this->assertSame($expectedData['name'], $entity->getName());
        $this->assertSame($expectedData['identifier'], $entity->getIdentifier());
        $this->assertSame($expectedData['body'], $entity->getBody());

        $this->assertEntityAssets($expectedData, $entity);
    }

    /**
     * @param array      $expectedData
     * @param PageLayout $entity
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
