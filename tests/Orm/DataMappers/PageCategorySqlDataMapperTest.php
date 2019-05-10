<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMapper;

use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Framework\Orm\DataMappers\SqlTestCase;
use AbterPhp\Framework\Orm\MockIdGeneratorFactory;
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

        $sql0   = 'UPDATE page_categories AS page_categories SET deleted = ? WHERE (id = ?)'; // phpcs:ignore
        $values = [[1, \PDO::PARAM_INT], [$id, \PDO::PARAM_STR]];
        $this->prepare($this->writeConnectionMock, $sql0, $this->createWriteStatement($values), 0);

        $entity = new PageCategory($id, $name, $identifier);

        $sql1    = 'DELETE FROM user_groups_page_categories WHERE (page_category_id = ?)'; // phpcs:ignore
        $values1 = [[$id, \PDO::PARAM_STR]];
        $this->prepare($this->writeConnectionMock, $sql1, $this->createWriteStatement($values1), 1);

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id         = 'df6b4637-634e-4544-a167-2bddf3eab498';
        $identifier = 'foo';
        $name       = 'bar';

        $sql          = 'SELECT pc.id, pc.name, pc.identifier, GROUP_CONCAT(ugpc.user_group_id) AS user_group_ids FROM page_categories AS pc LEFT JOIN user_groups_page_categories AS ugpc ON ugpc.page_category_id = pc.id WHERE (pc.deleted = 0) GROUP BY pc.id'; // phpcs:ignore
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

        $sql          = 'SELECT pc.id, pc.name, pc.identifier, GROUP_CONCAT(ugpc.user_group_id) AS user_group_ids FROM page_categories AS pc LEFT JOIN user_groups_page_categories AS ugpc ON ugpc.page_category_id = pc.id WHERE (pc.deleted = 0) AND (pc.id = :category_id) GROUP BY pc.id'; // phpcs:ignore
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

        $sql          = 'SELECT pc.id, pc.name, pc.identifier, GROUP_CONCAT(ugpc.user_group_id) AS user_group_ids FROM page_categories AS pc LEFT JOIN user_groups_page_categories AS ugpc ON ugpc.page_category_id = pc.id WHERE (pc.deleted = 0) AND (identifier = :identifier) GROUP BY pc.id'; // phpcs:ignore
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

    public function testUpdateWithoutUserGroup()
    {
        $id         = 'de8f969e-381e-4655-89db-46c8a7793bb3';
        $identifier = 'bar';
        $name       = 'foo';

        $sql0    = 'UPDATE page_categories AS page_categories SET name = ?, identifier = ? WHERE (id = ?) AND (deleted = 0)'; // phpcs:ignore
        $values0 = [
            [$name, \PDO::PARAM_STR],
            [$identifier, \PDO::PARAM_STR],
            [$id, \PDO::PARAM_STR],
        ];
        $this->prepare($this->writeConnectionMock, $sql0, $this->createWriteStatement($values0), 0);

        $entity = new PageCategory($id, $name, $identifier);

        $sql1    = 'DELETE FROM user_groups_page_categories WHERE (page_category_id = ?)'; // phpcs:ignore
        $values1 = [
            [$id, \PDO::PARAM_STR],
        ];
        $this->prepare($this->writeConnectionMock, $sql1, $this->createWriteStatement($values1), 1);

        $this->sut->update($entity);
    }

    public function testUpdateWithUserGroup()
    {
        $id         = 'a441487b-0bee-4137-8f76-c2a2b8d8c058';
        $identifier = 'bar';
        $name       = 'foo';
        $ugpc0      = '6ac51550-d682-44b3-906e-0a8dac6f555f';
        $ugpc1      = '5791b3e6-18ce-4132-9ec1-d31a26a22c3d';
        $userGroups = [
            new UserGroup('4206761a-00f9-4285-8721-da7d2a1677bf', '', ''),
            new UserGroup('15e94e76-dc94-47fa-87f4-db97995d195e', '', ''),
        ];

        $this->sut->setIdGenerator(MockIdGeneratorFactory::create($this, $ugpc0, $ugpc1));

        $sql0    = 'UPDATE page_categories AS page_categories SET name = ?, identifier = ? WHERE (id = ?) AND (deleted = 0)'; // phpcs:ignore
        $values0 = [
            [$name, \PDO::PARAM_STR],
            [$identifier, \PDO::PARAM_STR],
            [$id, \PDO::PARAM_STR],
        ];
        $this->prepare($this->writeConnectionMock, $sql0, $this->createWriteStatement($values0), 0);

        $entity = new PageCategory($id, $name, $identifier, $userGroups);

        $sql1    = 'DELETE FROM user_groups_page_categories WHERE (page_category_id = ?)'; // phpcs:ignore
        $values1 = [
            [$id, \PDO::PARAM_STR],
        ];
        $this->prepare($this->writeConnectionMock, $sql1, $this->createWriteStatement($values1), 1);

        $sql2    = 'INSERT INTO user_groups_page_categories (id, user_group_id, page_category_id) VALUES (?, ?, ?)'; // phpcs:ignore
        $values2 = [
            [$ugpc0, \PDO::PARAM_STR],
            [$userGroups[0]->getId(), \PDO::PARAM_STR],
            [$id, \PDO::PARAM_STR],
        ];
        $this->prepare($this->writeConnectionMock, $sql2, $this->createWriteStatement($values2), 2);

        $sql3    = 'INSERT INTO user_groups_page_categories (id, user_group_id, page_category_id) VALUES (?, ?, ?)'; // phpcs:ignore
        $values3 = [
            [$ugpc1, \PDO::PARAM_STR],
            [$userGroups[1]->getId(), \PDO::PARAM_STR],
            [$id, \PDO::PARAM_STR],
        ];
        $this->prepare($this->writeConnectionMock, $sql3, $this->createWriteStatement($values3), 3);

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
