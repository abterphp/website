<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMapper;

use AbterPhp\Framework\Orm\DataMappers\SqlTestCase;
use AbterPhp\Website\Domain\Entities\PageCategory;
use AbterPhp\Website\Orm\DataMappers\PageCategorySqlDataMapper;

class PageCategorySqlDataMapperTest extends SqlTestCase
{
    /** @var PageCategorySqlDataMapper */
    protected $sut;

    public function setUp()
    {
        parent::setUp();

        $this->sut = new PageCategorySqlDataMapper($this->readConnectionMock, $this->writeConnectionMock);
    }

    public function testAdd()
    {
        $nextId     = 'c840500d-bd00-410a-912e-e923b8e965e3';
        $identifier = 'foo';
        $name       = 'bar';

        $sql    = 'INSERT INTO page_categories (id, name, identifier) VALUES (?, ?, ?)'; // phpcs:ignore
        $values = [[$nextId, \PDO::PARAM_STR], [$name, \PDO::PARAM_STR], [$identifier, \PDO::PARAM_STR]];

        $this->prepare($this->writeConnectionMock, $sql, $this->createWriteStatement($values));
        $entity = new PageCategory($nextId, $name, $identifier);

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testDelete()
    {
        $id         = '1dab2760-9aaa-4f36-a303-42b12e65d165';
        $identifier = 'foo';
        $name       = 'bar';

        $sql    = 'UPDATE page_categories AS page_categories SET deleted = ? WHERE (id = ?)'; // phpcs:ignore
        $values = [[1, \PDO::PARAM_INT], [$id, \PDO::PARAM_STR]];

        $this->prepare($this->writeConnectionMock, $sql, $this->createWriteStatement($values));
        $entity = new PageCategory($id, $name, $identifier);

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id         = 'df6b4637-634e-4544-a167-2bddf3eab498';
        $identifier = 'foo';
        $name       = 'bar';

        $sql          = 'SELECT page_categories.id, page_categories.name, page_categories.identifier FROM page_categories WHERE (page_categories.deleted = 0)'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'         => $id,
                'name'       => $name,
                'identifier' => $identifier,
            ],
        ];

        $this->prepare($this->readConnectionMock, $sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getAll();

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetById()
    {
        $id         = 'fc2bdb23-bdd1-49aa-8613-b5e0ce76450d';
        $identifier = 'foo';
        $name       = 'bar';

        $sql          = 'SELECT page_categories.id, page_categories.name, page_categories.identifier FROM page_categories WHERE (page_categories.deleted = 0) AND (page_categories.id = :category_id)'; // phpcs:ignore
        $values       = ['category_id' => [$id, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'         => $id,
                'name'       => $name,
                'identifier' => $identifier,
            ],
        ];

        $this->prepare($this->readConnectionMock, $sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetByIdentifier()
    {
        $id         = '4a7847c6-d202-4fc7-972b-bd4d634efda1';
        $identifier = 'foo';
        $name       = 'bar';

        $sql          = 'SELECT page_categories.id, page_categories.name, page_categories.identifier FROM page_categories WHERE (page_categories.deleted = 0) AND (identifier = :identifier)'; // phpcs:ignore
        $values       = ['identifier' => $identifier];
        $expectedData = [
            [
                'id'         => $id,
                'name'       => $name,
                'identifier' => $identifier,
            ],
        ];

        $this->prepare($this->readConnectionMock, $sql, $this->createReadStatement($values, $expectedData));

        $actualResult = $this->sut->getByIdentifier($identifier);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testUpdate()
    {
        $id         = 'e62dd68b-c72c-464e-8e03-6ee86f26f592';
        $identifier = 'foo';
        $name       = 'bar';

        $sql    = 'UPDATE page_categories AS page_categories SET name = ?, identifier = ? WHERE (id = ?) AND (deleted = 0)'; // phpcs:ignore
        $values = [
            [$name, \PDO::PARAM_STR],
            [$identifier, \PDO::PARAM_STR],
            [$id, \PDO::PARAM_STR],
        ];

        $this->prepare($this->writeConnectionMock, $sql, $this->createWriteStatement($values));
        $entity = new PageCategory($id, $name, $identifier);

        $this->sut->update($entity);
    }

    /**
     * @param array        $expectedData
     * @param PageCategory $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
        $this->assertInstanceOf(PageCategory::class, $entity);
        $this->assertSame($expectedData['id'], $entity->getId());
        $this->assertSame($expectedData['identifier'], $entity->getIdentifier());
        $this->assertSame($expectedData['name'], $entity->getName());
    }
}
