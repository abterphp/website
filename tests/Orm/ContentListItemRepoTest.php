<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm;

use AbterPhp\Admin\TestCase\Orm\RepoTestCase;
use AbterPhp\Website\Domain\Entities\ContentListItem as Entity;
use AbterPhp\Website\Orm\DataMappers\ContentListItemSqlDataMapper as DataMapper;
use AbterPhp\Website\Orm\ContentListItemRepo as ItemRepo;
use Opulence\Orm\DataMappers\IDataMapper;
use Opulence\Orm\IEntityRegistry;
use PHPUnit\Framework\MockObject\MockObject;

class ContentListItemRepoTest extends RepoTestCase
{
    /** @var ItemRepo - System Under Test */
    protected $sut;

    /** @var DataMapper|MockObject */
    protected $dataMapperMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new ItemRepo($this->className, $this->dataMapperMock, $this->unitOfWorkMock);
    }

    /**
     * @return DataMapper|MockObject
     */
    protected function createDataMapperMock(): IDataMapper
    {
        /** @var DataMapper|MockObject $mock */
        $mock = $this->createMock(DataMapper::class);

        return $mock;
    }

    public function testGetAll()
    {
        $listId = 'bar0';

        $entityStub0 = new Entity('foo0', $listId, '', '', '', '', '', '', '', '');
        $entityStub1 = new Entity('foo1', $listId, '', '', '', '', '', '', '', '');
        $entities    = [$entityStub0, $entityStub1];

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getAll')->willReturn($entities);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getAll();

        $this->assertSame($entities, $actualResult);
    }

    public function testGetByIdFromCache()
    {
        $listId = 'bar0';

        $entityStub = new Entity('foo0', $listId, '', '', '', '', '', '', '', '');

        $entityRegistry = $this->createEntityRegistryStub($entityStub);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $this->dataMapperMock->expects($this->never())->method('getById');

        $id = 'foo';

        $actualResult = $this->sut->getById($id);

        $this->assertSame($entityStub, $actualResult);
    }

    public function testGetByIdFromDataMapper()
    {
        $listId = 'bar0';

        $entityStub = new Entity('foo0', $listId, '', '', '', '', '', '', '', '');

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $this->dataMapperMock->expects($this->once())->method('getById')->willReturn($entityStub);

        $id = 'foo';

        $actualResult = $this->sut->getById($id);

        $this->assertSame($entityStub, $actualResult);
    }

    public function testAdd()
    {
        $listId = 'bar0';

        $entityStub = new Entity('foo0', $listId, '', '', '', '', '', '', '', '');

        $this->unitOfWorkMock->expects($this->once())->method('scheduleForInsertion')->with($entityStub);

        $this->sut->add($entityStub);
    }

    public function testDelete()
    {
        $listId = 'bar0';

        $entityStub = new Entity('foo0', $listId, '', '', '', '', '', '', '', '');

        $this->unitOfWorkMock->expects($this->once())->method('scheduleForDeletion')->with($entityStub);

        $this->sut->delete($entityStub);
    }

    public function testGetPage()
    {
        $listId = 'bar0';

        $entityStub0 = new Entity('foo0', $listId, '', '', '', '', '', '', '', '');
        $entityStub1 = new Entity('foo1', $listId, '', '', '', '', '', '', '', '');
        $entities    = [$entityStub0, $entityStub1];

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getPage')->willReturn($entities);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getPage(0, 10, [], [], []);

        $this->assertSame($entities, $actualResult);
    }

    public function testGetByListId()
    {
        $listId = 'bar0';

        $entityStub0 = new Entity('foo0', $listId, '', '', '', '', '', '', '', '');
        $entityStub1 = new Entity('foo1', $listId, '', '', '', '', '', '', '', '');
        $entities    = [$entityStub0, $entityStub1];

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getByListId')->willReturn($entities);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getByListId($listId);

        $this->assertSame($entities, $actualResult);
    }

    public function testGetByListIds()
    {
        $listId0 = 'bar0';
        $listId1 = 'bar1';

        $entityStub0 = new Entity('foo0', $listId0, '', '', '', '', '', '', '', '');
        $entityStub1 = new Entity('foo1', $listId1, '', '', '', '', '', '', '', '');
        $entities    = [$entityStub0, $entityStub1];

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getByListIds')->willReturn($entities);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getByListIds([$listId0, $listId1]);

        $this->assertSame($entities, $actualResult);
    }

    /**
     * @param Entity|null $entity
     *
     * @return MockObject
     */
    protected function createEntityRegistryStub(?Entity $entity): MockObject
    {
        $entityRegistry = $this->createMock(IEntityRegistry::class);
        $entityRegistry->expects($this->any())->method('registerEntity');
        $entityRegistry->expects($this->any())->method('getEntity')->willReturn($entity);

        return $entityRegistry;
    }
}
