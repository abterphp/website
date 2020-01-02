<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMapper;

use AbterPhp\Admin\TestCase\Orm\DataMapperTestCase;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\TestDouble\Database\MockStatementFactory;
use AbterPhp\Website\Domain\Entities\ContentListType as Entity;
use AbterPhp\Website\Orm\DataMappers\ContentListTypeSqlDataMapper as DataMapper;
use PHPUnit\Framework\MockObject\MockObject;

class ContentListTypeSqlDataMapperTest extends DataMapperTestCase
{
    /** @var DataMapper - System Under Test */
    protected $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new DataMapper($this->readConnectionMock, $this->writeConnectionMock);
    }

    public function testAdd()
    {
        $nextId = '9d160dd2-bd83-48f6-a3b0-e15d2f26e76c';
        $name   = 'foo';
        $label  = 'bar';

        $sql       = 'INSERT INTO list_types (id, name, label) VALUES (?, ?, ?)'; // phpcs:ignore
        $values    = [
            [$nextId, \PDO::PARAM_STR],
            [$name, \PDO::PARAM_STR],
            [$label, \PDO::PARAM_STR],
        ];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $entity = new Entity($nextId, $name, $label);

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testDelete()
    {
        $id = '9d160dd2-bd83-48f6-a3b0-e15d2f26e76c';

        $sql       = 'UPDATE list_types AS list_types SET deleted_at = NOW() WHERE (id = ?)'; // phpcs:ignore
        $values    = [[$id, \PDO::PARAM_STR]];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $entity = new Entity($id, '', '');

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id    = '9d160dd2-bd83-48f6-a3b0-e15d2f26e76c';
        $name  = 'foo';
        $label = 'bar';

        $sql          = 'SELECT list_types.id, list_types.name, list_types.label FROM list_types WHERE (list_types.deleted_at IS NULL)'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'    => $id,
                'name'  => $name,
                'label' => $label,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getAll();

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetPage()
    {
        $id    = '9d160dd2-bd83-48f6-a3b0-e15d2f26e76c';
        $name  = 'foo';
        $label = 'bar';

        $sql          = 'SELECT SQL_CALC_FOUND_ROWS list_types.id, list_types.name, list_types.label FROM list_types WHERE (list_types.deleted_at IS NULL) LIMIT 10 OFFSET 0'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'    => $id,
                'name'  => $name,
                'label' => $label,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getPage(0, 10, [], [], []);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetPageWithOrdersAndConditions()
    {
        $id    = '9d160dd2-bd83-48f6-a3b0-e15d2f26e76c';
        $name  = 'foo';
        $label = 'bar';

        $orders     = ['list_types.name ASC'];
        $conditions = ['list_types.name LIKE \'abc%\'', 'list_types.name LIKE \'%bca\''];

        $sql          = 'SELECT SQL_CALC_FOUND_ROWS list_types.id, list_types.name, list_types.label FROM list_types WHERE (list_types.deleted_at IS NULL) AND (list_types.name LIKE \'abc%\') AND (list_types.name LIKE \'%bca\') ORDER BY list_types.name ASC LIMIT 10 OFFSET 0'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'    => $id,
                'name'  => $name,
                'label' => $label,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getPage(0, 10, $orders, $conditions, []);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetById()
    {
        $id    = '9d160dd2-bd83-48f6-a3b0-e15d2f26e76c';
        $name  = 'foo';
        $label = 'bar';

        $sql          = 'SELECT list_types.id, list_types.name, list_types.label FROM list_types WHERE (list_types.deleted_at IS NULL) AND (list_types.id = :list_type_id)'; // phpcs:ignore
        $values       = ['list_type_id' => [$id, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'    => $id,
                'name'  => $name,
                'label' => $label,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testUpdate()
    {
        $id    = '9d160dd2-bd83-48f6-a3b0-e15d2f26e76c';
        $name  = 'foo';
        $label = 'bar';

        $sql       = 'UPDATE list_types AS list_types SET name = ?, label = ? WHERE (id = ?) AND (list_types.deleted_at IS NULL)'; // phpcs:ignore
        $values    = [
            [$name, \PDO::PARAM_STR],
            [$label, \PDO::PARAM_STR],
            [$id, \PDO::PARAM_STR],
        ];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $entity = new Entity($id, $name, $label);

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
     * @param array  $expectedData
     * @param Entity $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
        $this->assertInstanceOf(Entity::class, $entity);
        $this->assertSame($expectedData['id'], $entity->getId());
        $this->assertSame($expectedData['name'], $entity->getName());
        $this->assertSame($expectedData['label'], $entity->getLabel());
    }
}
