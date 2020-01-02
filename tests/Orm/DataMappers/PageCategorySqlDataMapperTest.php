<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMapper;

use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Admin\TestCase\Orm\DataMapperTestCase;
use AbterPhp\Admin\TestDouble\Orm\MockIdGeneratorFactory;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\TestDouble\Database\MockStatementFactory;
use AbterPhp\Website\Domain\Entities\PageCategory;
use AbterPhp\Website\Orm\DataMappers\PageCategorySqlDataMapper;
use PHPUnit\Framework\MockObject\MockObject;

class PageCategorySqlDataMapperTest extends DataMapperTestCase
{
    /** @var PageCategorySqlDataMapper - System Under Test */
    protected $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new PageCategorySqlDataMapper($this->readConnectionMock, $this->writeConnectionMock);
    }

    public function testAdd()
    {
        $nextId     = 'c840500d-bd00-410a-912e-e923b8e965e3';
        $identifier = 'foo';
        $name       = 'bar';

        $sql       = 'INSERT INTO page_categories (id, name, identifier) VALUES (?, ?, ?)'; // phpcs:ignore
        $values    = [[$nextId, \PDO::PARAM_STR], [$name, \PDO::PARAM_STR], [$identifier, \PDO::PARAM_STR]];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);
        $entity = new PageCategory($nextId, $name, $identifier);

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testDelete()
    {
        $id         = '1dab2760-9aaa-4f36-a303-42b12e65d165';
        $identifier = 'foo';
        $name       = 'bar';

        $sql0       = 'UPDATE page_categories AS page_categories SET deleted_at = NOW() WHERE (id = ?)'; // phpcs:ignore
        $values0    = [[$id, \PDO::PARAM_STR]];
        $statement0 = MockStatementFactory::createWriteStatement($this, $values0);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql0, $statement0, 0);

        $sql1       = 'DELETE FROM user_groups_page_categories WHERE (page_category_id = ?)'; // phpcs:ignore
        $values1    = [[$id, \PDO::PARAM_STR]];
        $statement1 = MockStatementFactory::createWriteStatement($this, $values1);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql1, $statement1, 1);

        $entity = new PageCategory($id, $name, $identifier);

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id         = 'df6b4637-634e-4544-a167-2bddf3eab498';
        $identifier = 'foo';
        $name       = 'bar';

        $sql          = 'SELECT pc.id, pc.name, pc.identifier, GROUP_CONCAT(ugpc.user_group_id) AS user_group_ids FROM page_categories AS pc LEFT JOIN user_groups_page_categories AS ugpc ON ugpc.page_category_id = pc.id WHERE (pc.deleted_at IS NULL) GROUP BY pc.id'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'         => $id,
                'name'       => $name,
                'identifier' => $identifier,
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
        $name       = 'bar';

        $sql          = 'SELECT SQL_CALC_FOUND_ROWS pc.id, pc.name, pc.identifier, GROUP_CONCAT(ugpc.user_group_id) AS user_group_ids FROM page_categories AS pc LEFT JOIN user_groups_page_categories AS ugpc ON ugpc.page_category_id = pc.id WHERE (pc.deleted_at IS NULL) GROUP BY pc.id LIMIT 10 OFFSET 0'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'         => $id,
                'name'       => $name,
                'identifier' => $identifier,
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
        $name       = 'bar';

        $orders     = ['pc.identifier ASC'];
        $conditions = ['pc.identifier LIKE \'abc%\'', 'pc.identifier LIKE \'%bca\''];

        $sql          = 'SELECT SQL_CALC_FOUND_ROWS pc.id, pc.name, pc.identifier, GROUP_CONCAT(ugpc.user_group_id) AS user_group_ids FROM page_categories AS pc LEFT JOIN user_groups_page_categories AS ugpc ON ugpc.page_category_id = pc.id WHERE (pc.deleted_at IS NULL) AND (pc.identifier LIKE \'abc%\') AND (pc.identifier LIKE \'%bca\') GROUP BY pc.id ORDER BY pc.identifier ASC LIMIT 10 OFFSET 0'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'         => $id,
                'name'       => $name,
                'identifier' => $identifier,
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
        $identifier = 'foo';
        $name       = 'bar';

        $sql          = 'SELECT pc.id, pc.name, pc.identifier, GROUP_CONCAT(ugpc.user_group_id) AS user_group_ids FROM page_categories AS pc LEFT JOIN user_groups_page_categories AS ugpc ON ugpc.page_category_id = pc.id WHERE (pc.deleted_at IS NULL) AND (pc.id = :category_id) GROUP BY pc.id'; // phpcs:ignore
        $values       = ['category_id' => [$id, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'         => $id,
                'name'       => $name,
                'identifier' => $identifier,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetByIdWithUserGroups()
    {
        $id         = 'fc2bdb23-bdd1-49aa-8613-b5e0ce76450d';
        $identifier = 'foo';
        $name       = 'bar';

        $ugId0 = '92dcb09a-eb3b-49b8-96c2-1a37818c780c';
        $ugId1 = '2f962fe9-7e5b-4e06-a02f-bcd68152a83c';

        $sql          = 'SELECT pc.id, pc.name, pc.identifier, GROUP_CONCAT(ugpc.user_group_id) AS user_group_ids FROM page_categories AS pc LEFT JOIN user_groups_page_categories AS ugpc ON ugpc.page_category_id = pc.id WHERE (pc.deleted_at IS NULL) AND (pc.id = :category_id) GROUP BY pc.id'; // phpcs:ignore
        $values       = ['category_id' => [$id, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'             => $id,
                'name'           => $name,
                'identifier'     => $identifier,
                'user_group_ids' => "$ugId0,$ugId1",
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
        $identifier = 'foo';
        $name       = 'bar';

        $sql          = 'SELECT pc.id, pc.name, pc.identifier, GROUP_CONCAT(ugpc.user_group_id) AS user_group_ids FROM page_categories AS pc LEFT JOIN user_groups_page_categories AS ugpc ON ugpc.page_category_id = pc.id WHERE (pc.deleted_at IS NULL) AND (identifier = :identifier) GROUP BY pc.id'; // phpcs:ignore
        $values       = ['identifier' => $identifier];
        $expectedData = [
            [
                'id'         => $id,
                'name'       => $name,
                'identifier' => $identifier,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getByIdentifier($identifier);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testUpdateWithoutUserGroup()
    {
        $id         = 'de8f969e-381e-4655-89db-46c8a7793bb3';
        $identifier = 'bar';
        $name       = 'foo';

        $sql0       = 'UPDATE page_categories AS page_categories SET name = ?, identifier = ? WHERE (id = ?) AND (deleted_at IS NULL)'; // phpcs:ignore
        $values0    = [
            [$name, \PDO::PARAM_STR],
            [$identifier, \PDO::PARAM_STR],
            [$id, \PDO::PARAM_STR],
        ];
        $statement0 = MockStatementFactory::createWriteStatement($this, $values0);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql0, $statement0, 0);

        $sql1       = 'DELETE FROM user_groups_page_categories WHERE (page_category_id = ?)'; // phpcs:ignore
        $values1    = [
            [$id, \PDO::PARAM_STR],
        ];
        $statement1 = MockStatementFactory::createWriteStatement($this, $values1);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql1, $statement1, 1);

        $entity = new PageCategory($id, $name, $identifier);

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

        $sql0       = 'UPDATE page_categories AS page_categories SET name = ?, identifier = ? WHERE (id = ?) AND (deleted_at IS NULL)'; // phpcs:ignore
        $values0    = [
            [$name, \PDO::PARAM_STR],
            [$identifier, \PDO::PARAM_STR],
            [$id, \PDO::PARAM_STR],
        ];
        $statement0 = MockStatementFactory::createWriteStatement($this, $values0);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql0, $statement0, 0);

        $sql1       = 'DELETE FROM user_groups_page_categories WHERE (page_category_id = ?)'; // phpcs:ignore
        $values1    = [
            [$id, \PDO::PARAM_STR],
        ];
        $statement1 = MockStatementFactory::createWriteStatement($this, $values1);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql1, $statement1, 1);

        $sql2       = 'INSERT INTO user_groups_page_categories (id, user_group_id, page_category_id) VALUES (?, ?, ?)'; // phpcs:ignore
        $values2    = [
            [$ugpc0, \PDO::PARAM_STR],
            [$userGroups[0]->getId(), \PDO::PARAM_STR],
            [$id, \PDO::PARAM_STR],
        ];
        $statement2 = MockStatementFactory::createWriteStatement($this, $values2);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql2, $statement2, 2);

        $sql3       = 'INSERT INTO user_groups_page_categories (id, user_group_id, page_category_id) VALUES (?, ?, ?)'; // phpcs:ignore
        $values3    = [
            [$ugpc1, \PDO::PARAM_STR],
            [$userGroups[1]->getId(), \PDO::PARAM_STR],
            [$id, \PDO::PARAM_STR],
        ];
        $statement3 = MockStatementFactory::createWriteStatement($this, $values3);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql3, $statement3, 3);

        $entity = new PageCategory($id, $name, $identifier, $userGroups);

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
     * @param array        $expectedData
     * @param PageCategory $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
        $this->assertInstanceOf(PageCategory::class, $entity);
        $this->assertSame($expectedData['id'], $entity->getId());
        $this->assertSame($expectedData['identifier'], $entity->getIdentifier());
        $this->assertSame($expectedData['name'], $entity->getName());

        $this->assertUserGroups($expectedData, $entity);
    }

    /**
     * @param array        $expectedData
     * @param PageCategory $entity
     */
    protected function assertUserGroups(array $expectedData, $entity)
    {
        if (empty($expectedData['user_group_ids'])) {
            return;
        }

        $ugIds = [];
        foreach ($entity->getUserGroups() as $ug) {
            $ugIds[] = $ug->getId();
        }

        $this->assertSame($expectedData['user_group_ids'], implode(',', $ugIds));
    }
}
