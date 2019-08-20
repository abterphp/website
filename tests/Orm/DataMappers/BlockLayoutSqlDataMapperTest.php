<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMapper;

use AbterPhp\Admin\TestCase\Orm\DataMapperTestCase;
use AbterPhp\Framework\TestDouble\Database\MockStatementFactory;
use AbterPhp\Website\Domain\Entities\BlockLayout;
use AbterPhp\Website\Orm\DataMappers\BlockLayoutSqlDataMapper;

class BlockLayoutSqlDataMapperTest extends DataMapperTestCase
{
    /** @var BlockLayoutSqlDataMapper */
    protected $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new BlockLayoutSqlDataMapper($this->readConnectionMock, $this->writeConnectionMock);
    }

    public function testAdd()
    {
        $nextId     = 'c2883287-ae5d-42d1-ab0c-7d3da2846452';
        $identifier = 'foo';
        $body       = 'bar';

        $sql       = 'INSERT INTO block_layouts (id, identifier, body) VALUES (?, ?, ?)'; // phpcs:ignore
        $values    = [[$nextId, \PDO::PARAM_STR], [$identifier, \PDO::PARAM_STR], [$body, \PDO::PARAM_STR]];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $entity = new BlockLayout($nextId, $identifier, $body);

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testDelete()
    {
        $id         = 'd23a94ed-b75c-43a9-9987-a783183dadd5';
        $identifier = 'foo';
        $body       = 'bar';

        $sql       = 'UPDATE block_layouts AS block_layouts SET deleted = ? WHERE (id = ?)'; // phpcs:ignore
        $values    = [[1, \PDO::PARAM_INT], [$id, \PDO::PARAM_STR]];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $entity = new BlockLayout($id, $identifier, $body);

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id         = '40a59d7d-7550-4b16-a90b-89adbfec8979';
        $identifier = 'foo';
        $body       = 'bar';

        $sql          = 'SELECT block_layouts.id, block_layouts.identifier, block_layouts.body FROM block_layouts WHERE (block_layouts.deleted = 0)'; // phpcs:ignore
        $values       = [];
        $expectedData = [['id' => $id, 'identifier' => $identifier, 'body' => $body]];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getAll();

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetById()
    {
        $id         = 'adbeb333-3110-42ec-a2ed-74a33db518ff';
        $identifier = 'foo';
        $body       = 'bar';

        $sql          = 'SELECT block_layouts.id, block_layouts.identifier, block_layouts.body FROM block_layouts WHERE (block_layouts.deleted = 0) AND (block_layouts.id = :layout_id)'; // phpcs:ignore
        $values       = ['layout_id' => [$id, \PDO::PARAM_STR]];
        $expectedData = [['id' => $id, 'identifier' => $identifier, 'body' => $body]];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetByIdentifier()
    {
        $id         = 'b0538bd0-5762-417c-8208-4e6b04b72f86';
        $identifier = 'foo';
        $body       = 'bar';

        $sql          = 'SELECT block_layouts.id, block_layouts.identifier, block_layouts.body FROM block_layouts WHERE (block_layouts.deleted = 0) AND (identifier = :identifier)'; // phpcs:ignore
        $values       = ['identifier' => $identifier];
        $expectedData = [['id' => $id, 'identifier' => $identifier, 'body' => $body]];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getByIdentifier($identifier);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testUpdate()
    {
        $id         = '10ada92f-9ed8-4b7b-897a-9e10c640caec';
        $identifier = 'foo';
        $body       = 'bar';

        $sql       = 'UPDATE block_layouts AS block_layouts SET identifier = ?, body = ? WHERE (id = ?) AND (deleted = 0)'; // phpcs:ignore
        $values    = [[$identifier, \PDO::PARAM_STR], [$body, \PDO::PARAM_STR], [$id, \PDO::PARAM_STR]];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $entity = new BlockLayout($id, $identifier, $body);

        $this->sut->update($entity);
    }

    /**
     * @param array       $expectedData
     * @param BlockLayout $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
        $this->assertInstanceOf(BlockLayout::class, $entity);
        $this->assertSame($expectedData['id'], $entity->getId());
        $this->assertSame($expectedData['identifier'], $entity->getIdentifier());
        $this->assertSame($expectedData['body'], $entity->getBody());
    }
}
