<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMapper;

use AbterPhp\Admin\TestCase\Orm\DataMapperTestCase;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\TestDouble\Database\MockStatementFactory;
use AbterPhp\Website\Domain\Entities\Block as Entity;
use AbterPhp\Website\Orm\DataMappers\BlockSqlDataMapper as DataMapper;
use PHPUnit\Framework\MockObject\MockObject;

class BlockSqlDataMapperTest extends DataMapperTestCase
{
    /** @var DataMapper - System Under Test */
    protected $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new DataMapper($this->readConnectionMock, $this->writeConnectionMock);
    }

    public function testAddWithoutLayoutId()
    {
        $nextId     = '9d160dd2-bd83-48f6-a3b0-e15d2f26e76c';
        $identifier = 'foo';
        $title      = 'bar';
        $body       = 'baz';
        $layout     = 'qux';
        $layoutId   = null;

        $sql       = 'INSERT INTO blocks (id, identifier, title, body, layout, layout_id) VALUES (?, ?, ?, ?, ?, ?)'; // phpcs:ignore
        $values    = [
            [$nextId, \PDO::PARAM_STR],
            [$identifier, \PDO::PARAM_STR],
            [$title, \PDO::PARAM_STR],
            [$body, \PDO::PARAM_STR],
            [$layout, \PDO::PARAM_STR],
            [$layoutId, \PDO::PARAM_NULL],
        ];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $entity = new Entity($nextId, $identifier, $title, $body, $layout, $layoutId);

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testAddWithLayoutId()
    {
        $nextId     = '5b6c9874-bb65-4a6f-a5f1-78445434db84';
        $identifier = 'foo';
        $title      = 'bar';
        $body       = 'baz';
        $layout     = '';
        $layoutId   = 'ae749110-e1f9-4f76-9391-5fe3b28a0b0d';

        $sql       = 'INSERT INTO blocks (id, identifier, title, body, layout, layout_id) VALUES (?, ?, ?, ?, ?, ?)'; // phpcs:ignore
        $values    = [
            [$nextId, \PDO::PARAM_STR],
            [$identifier, \PDO::PARAM_STR],
            [$title, \PDO::PARAM_STR],
            [$body, \PDO::PARAM_STR],
            [$layout, \PDO::PARAM_STR],
            [$layoutId, \PDO::PARAM_STR],
        ];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $entity = new Entity($nextId, $identifier, $title, $body, $layout, $layoutId);

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testDelete()
    {
        $id         = 'f95ffd21-eff5-4b10-a423-e222fb7fe56f';
        $identifier = 'foo';
        $title      = 'bar';
        $body       = 'baz';
        $layout     = 'qux';
        $layoutId   = null;

        $sql       = 'UPDATE blocks AS blocks SET deleted_at = NOW() WHERE (id = ?)'; // phpcs:ignore
        $values    = [[$id, \PDO::PARAM_STR]];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $entity = new Entity($id, $identifier, $title, $body, $layout, $layoutId);

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id         = '8da63e49-5c76-4520-9280-30c125305239';
        $identifier = 'foo';
        $title      = 'bar';
        $body       = 'baz';
        $layout     = 'qux';
        $layoutId   = null;

        $sql          = 'SELECT blocks.id, blocks.identifier, blocks.title, blocks.body, blocks.layout_id, blocks.layout FROM blocks WHERE (blocks.deleted_at IS NULL)'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'         => $id,
                'identifier' => $identifier,
                'title'      => $title,
                'body'       => $body,
                'layout'     => $layout,
                'layout_id'  => $layoutId,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getAll();

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetPage()
    {
        $id         = '8da63e49-5c76-4520-9280-30c125305239';
        $identifier = 'foo';
        $title      = 'bar';
        $body       = 'baz';
        $layout     = 'qux';
        $layoutId   = null;

        $sql          = 'SELECT SQL_CALC_FOUND_ROWS blocks.id, blocks.identifier, blocks.title, blocks.body, blocks.layout_id, blocks.layout FROM blocks WHERE (blocks.deleted_at IS NULL) ORDER BY title ASC LIMIT 10 OFFSET 0'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'         => $id,
                'identifier' => $identifier,
                'title'      => $title,
                'body'       => $body,
                'layout'     => $layout,
                'layout_id'  => $layoutId,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getPage(0, 10, [], [], []);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetPageWithOrdersAndConditions()
    {
        $id         = '8da63e49-5c76-4520-9280-30c125305239';
        $identifier = 'foo';
        $title      = 'bar';
        $body       = 'baz';
        $layout     = 'qux';
        $layoutId   = null;

        $orders     = ['blocks.identifier ASC'];
        $conditions = ['blocks.identifier LIKE \'abc%\'', 'blocks.identifier LIKE \'%bca\''];

        $sql          = 'SELECT SQL_CALC_FOUND_ROWS blocks.id, blocks.identifier, blocks.title, blocks.body, blocks.layout_id, blocks.layout FROM blocks WHERE (blocks.deleted_at IS NULL) AND (blocks.identifier LIKE \'abc%\') AND (blocks.identifier LIKE \'%bca\') ORDER BY blocks.identifier ASC LIMIT 10 OFFSET 0'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'         => $id,
                'identifier' => $identifier,
                'title'      => $title,
                'body'       => $body,
                'layout'     => $layout,
                'layout_id'  => $layoutId,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getPage(0, 10, $orders, $conditions, []);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetById()
    {
        $id         = 'da406cd9-4a65-4384-b1dd-454c4d26c196';
        $identifier = 'foo';
        $title      = 'bar';
        $body       = 'baz';
        $layout     = 'qux';
        $layoutId   = null;

        $sql          = 'SELECT blocks.id, blocks.identifier, blocks.title, blocks.body, blocks.layout_id, blocks.layout FROM blocks WHERE (blocks.deleted_at IS NULL) AND (blocks.id = :block_id)'; // phpcs:ignore
        $values       = ['block_id' => [$id, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'         => $id,
                'identifier' => $identifier,
                'title'      => $title,
                'body'       => $body,
                'layout'     => $layout,
                'layout_id'  => $layoutId,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetByIdentifier()
    {
        $id         = '42f019b3-5d49-4ee0-b785-63ef245a1ee0';
        $identifier = 'foo';
        $title      = 'bar';
        $body       = 'baz';
        $layout     = 'qux';
        $layoutId   = null;

        $sql          = 'SELECT blocks.id, blocks.identifier, blocks.title, blocks.body, blocks.layout_id, blocks.layout FROM blocks WHERE (blocks.deleted_at IS NULL) AND (blocks.identifier = :identifier)'; // phpcs:ignore
        $values       = ['identifier' => [$identifier, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'         => $id,
                'identifier' => $identifier,
                'title'      => $title,
                'body'       => $body,
                'layout'     => $layout,
                'layout_id'  => $layoutId,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getByIdentifier($identifier);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetWithLayoutByIdentifiers()
    {
        $id         = '76815a32-359e-4898-996a-ef9695f875bb';
        $identifier = 'foo';
        $title      = 'bar';
        $body       = 'baz';
        $layout     = 'qux';
        $layoutId   = null;

        $sql          = 'SELECT blocks.id, blocks.identifier, blocks.title, blocks.body, blocks.layout_id, COALESCE(layouts.body, blocks.layout) AS layout FROM blocks LEFT JOIN block_layouts AS layouts ON layouts.id = blocks.layout_id AND layouts.deleted_at IS NULL WHERE (blocks.deleted_at IS NULL) AND (blocks.identifier IN (?))'; // phpcs:ignore
        $values       = [[$identifier, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'         => $id,
                'identifier' => $identifier,
                'title'      => $title,
                'body'       => $body,
                'layout'     => $layout,
                'layout_id'  => $layoutId,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getWithLayoutByIdentifiers([$identifier]);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetWithLayoutByIdentifiersCanReturnEarly()
    {
        $expectedData = [];

        $actualResult = $this->sut->getWithLayoutByIdentifiers([]);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testUpdateWithoutLayoutId()
    {
        $id         = 'f7cdde13-7f39-493e-9c7a-ddeab4adb8eb';
        $identifier = 'foo';
        $title      = 'bar';
        $body       = 'baz';
        $layout     = 'qux';
        $layoutId   = null;

        $sql       = 'UPDATE blocks AS blocks SET identifier = ?, title = ?, body = ?, layout = ?, layout_id = ? WHERE (id = ?) AND (deleted_at IS NULL)'; // phpcs:ignore
        $values    = [
            [$identifier, \PDO::PARAM_STR],
            [$title, \PDO::PARAM_STR],
            [$body, \PDO::PARAM_STR],
            [$layout, \PDO::PARAM_STR],
            [$layoutId, \PDO::PARAM_NULL],
            [$id, \PDO::PARAM_STR],
        ];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $entity = new Entity($id, $identifier, $title, $body, $layout, $layoutId);

        $this->sut->update($entity);
    }

    public function testUpdateWithLayoutId()
    {
        $id         = 'e0075c90-80ff-40dd-aafe-a8d866a42bcd';
        $identifier = 'foo';
        $title      = 'bar';
        $body       = 'baz';
        $layout     = '';
        $layoutId   = 'd7a7bcad-71bc-40a1-8a0d-dc2b28a54811';

        $sql       = 'UPDATE blocks AS blocks SET identifier = ?, title = ?, body = ?, layout = ?, layout_id = ? WHERE (id = ?) AND (deleted_at IS NULL)'; // phpcs:ignore
        $values    = [
            [$identifier, \PDO::PARAM_STR],
            [$title, \PDO::PARAM_STR],
            [$body, \PDO::PARAM_STR],
            [$layout, \PDO::PARAM_STR],
            [$layoutId, \PDO::PARAM_STR],
            [$id, \PDO::PARAM_STR],
        ];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $entity = new Entity($id, $identifier, $title, $body, $layout, $layoutId);

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
     * @param Entity $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
        $this->assertInstanceOf(Entity::class, $entity);
        $this->assertSame($expectedData['id'], $entity->getId());
        $this->assertSame($expectedData['identifier'], $entity->getIdentifier());
        $this->assertSame($expectedData['title'], $entity->getTitle());
        $this->assertSame($expectedData['body'], $entity->getBody());
        $this->assertSame($expectedData['layout'], $entity->getLayout());
        $this->assertSame($expectedData['layout_id'], $entity->getLayoutId());
    }
}
