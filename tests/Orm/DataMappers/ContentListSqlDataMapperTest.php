<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMapper;

use AbterPhp\Admin\TestCase\Orm\DataMapperTestCase;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\TestDouble\Database\MockStatementFactory;
use AbterPhp\Website\Domain\Entities\ContentList as Entity;
use AbterPhp\Website\Domain\Entities\ContentListType as Type;
use AbterPhp\Website\Orm\DataMappers\ContentListSqlDataMapper as DataMapper;
use PHPUnit\Framework\MockObject\MockObject;

class ContentListSqlDataMapperTest extends DataMapperTestCase
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
        $nextId     = '9d160dd2-bd83-48f6-a3b0-e15d2f26e76c';
        $typeId     = '5347626d-10bf-449d-803f-ae017bd4812d';
        $name       = 'Foo';
        $identifier = 'foo';
        $classes    = 'foo0 foo1';
        $protected  = false;
        $withLinks  = false;
        $withImage  = false;
        $withBody   = false;
        $withHtml   = false;

        $sql       = 'INSERT INTO lists (id, type_id, name, identifier, classes, protected, with_links, with_image, with_body, with_html) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'; // phpcs:ignore
        $values    = [
            [$nextId, \PDO::PARAM_STR],
            [$typeId, \PDO::PARAM_STR],
            [$name, \PDO::PARAM_STR],
            [$identifier, \PDO::PARAM_STR],
            [$classes, \PDO::PARAM_STR],
            [$protected, \PDO::PARAM_BOOL],
            [$withLinks, \PDO::PARAM_BOOL],
            [$withImage, \PDO::PARAM_BOOL],
            [$withBody, \PDO::PARAM_BOOL],
            [$withHtml, \PDO::PARAM_BOOL],
        ];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $entity = new Entity(
            $nextId,
            $name,
            $identifier,
            $classes,
            $protected,
            $withLinks,
            $withImage,
            $withBody,
            $withHtml,
            new Type($typeId, '', '')
        );

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testDelete()
    {
        $id = '9d160dd2-bd83-48f6-a3b0-e15d2f26e76c';

        $sql0       = 'UPDATE lists AS lists SET deleted_at = NOW() WHERE (id = ?)'; // phpcs:ignore
        $values0    = [[$id, \PDO::PARAM_STR]];
        $statement0 = MockStatementFactory::createWriteStatement($this, $values0);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql0, $statement0, 0);

        $sql1       = 'UPDATE list_items AS list_items SET deleted_at = NOW() WHERE (list_id = ?) AND (deleted_at IS NOT NULL)'; // phpcs:ignore
        $values1    = [[$id, \PDO::PARAM_STR]];
        $statement1 = MockStatementFactory::createWriteStatement($this, $values1);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql1, $statement1, 1);

        $entity = new Entity($id, '', '', '', false, false, false, false, false);

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id         = '8da63e49-5c76-4520-9280-30c125305239';
        $typeId     = '279bb345-f7dd-4232-b9d8-acc5628e9fd3';
        $name       = 'Foo';
        $identifier = 'foo';
        $classes    = 'foo0 foo1';
        $protected  = false;
        $withLinks  = false;
        $withImage  = false;
        $withBody   = false;
        $withHtml   = false;
        $typeName   = 'L 123';
        $typeLabel  = 'l123';

        $sql          = 'SELECT lists.id, lists.type_id, lists.name, lists.identifier, lists.classes, lists.protected, lists.with_links, lists.with_image, lists.with_body, lists.with_html, list_types.name AS type_name, list_types.label AS type_label FROM lists INNER JOIN list_types AS list_types ON list_types.id = lists.type_id AND list_types.deleted_at IS NULL WHERE (lists.deleted_at IS NULL)'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'         => $id,
                'type_id'    => $typeId,
                'name'       => $name,
                'identifier' => $identifier,
                'classes'    => $classes,
                'protected'  => $protected,
                'with_links' => $withLinks,
                'with_image' => $withImage,
                'with_body'  => $withBody,
                'with_html'  => $withHtml,
                'type_name'  => $typeName,
                'type_label' => $typeLabel,
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
        $typeId     = '279bb345-f7dd-4232-b9d8-acc5628e9fd3';
        $name       = 'Foo';
        $identifier = 'foo';
        $classes    = 'foo0 foo1';
        $protected  = false;
        $withLinks  = false;
        $withImage  = false;
        $withBody   = false;
        $withHtml   = false;
        $typeName   = 'L 123';
        $typeLabel  = 'l123';

        $sql          = 'SELECT SQL_CALC_FOUND_ROWS lists.id, lists.type_id, lists.name, lists.identifier, lists.classes, lists.protected, lists.with_links, lists.with_image, lists.with_body, lists.with_html, list_types.name AS type_name, list_types.label AS type_label FROM lists INNER JOIN list_types AS list_types ON list_types.id = lists.type_id AND list_types.deleted_at IS NULL WHERE (lists.deleted_at IS NULL) LIMIT 10 OFFSET 0'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'         => $id,
                'type_id'    => $typeId,
                'name'       => $name,
                'identifier' => $identifier,
                'classes'    => $classes,
                'protected'  => $protected,
                'with_links' => $withLinks,
                'with_image' => $withImage,
                'with_body'  => $withBody,
                'with_html'  => $withHtml,
                'type_name'  => $typeName,
                'type_label' => $typeLabel,
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
        $typeId     = '279bb345-f7dd-4232-b9d8-acc5628e9fd3';
        $name       = 'Foo';
        $identifier = 'foo';
        $classes    = 'foo0 foo1';
        $protected  = false;
        $withLinks  = false;
        $withImage  = false;
        $withBody   = false;
        $withHtml   = false;
        $typeName   = 'L 123';
        $typeLabel  = 'l123';

        $orders     = ['lists.identifier ASC'];
        $conditions = ['lists.identifier LIKE \'abc%\'', 'lists.identifier LIKE \'%bca\''];

        $sql          = 'SELECT SQL_CALC_FOUND_ROWS lists.id, lists.type_id, lists.name, lists.identifier, lists.classes, lists.protected, lists.with_links, lists.with_image, lists.with_body, lists.with_html, list_types.name AS type_name, list_types.label AS type_label FROM lists INNER JOIN list_types AS list_types ON list_types.id = lists.type_id AND list_types.deleted_at IS NULL WHERE (lists.deleted_at IS NULL) AND (lists.identifier LIKE \'abc%\') AND (lists.identifier LIKE \'%bca\') ORDER BY lists.identifier ASC LIMIT 10 OFFSET 0'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'         => $id,
                'type_id'    => $typeId,
                'name'       => $name,
                'identifier' => $identifier,
                'classes'    => $classes,
                'protected'  => $protected,
                'with_links' => $withLinks,
                'with_image' => $withImage,
                'with_body'  => $withBody,
                'with_html'  => $withHtml,
                'type_name'  => $typeName,
                'type_label' => $typeLabel,
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
        $typeId     = '279bb345-f7dd-4232-b9d8-acc5628e9fd3';
        $name       = 'Foo';
        $identifier = 'foo';
        $classes    = 'foo0 foo1';
        $protected  = false;
        $withLinks  = false;
        $withImage  = false;
        $withBody   = false;
        $withHtml   = false;
        $typeName   = 'L 123';
        $typeLabel  = 'l123';

        $sql          = 'SELECT lists.id, lists.type_id, lists.name, lists.identifier, lists.classes, lists.protected, lists.with_links, lists.with_image, lists.with_body, lists.with_html, list_types.name AS type_name, list_types.label AS type_label FROM lists INNER JOIN list_types AS list_types ON list_types.id = lists.type_id AND list_types.deleted_at IS NULL WHERE (lists.deleted_at IS NULL) AND (lists.id = :id)'; // phpcs:ignore
        $values       = ['id' => [$id, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'         => $id,
                'type_id'    => $typeId,
                'name'       => $name,
                'identifier' => $identifier,
                'classes'    => $classes,
                'protected'  => $protected,
                'with_links' => $withLinks,
                'with_image' => $withImage,
                'with_body'  => $withBody,
                'with_html'  => $withHtml,
                'type_name'  => $typeName,
                'type_label' => $typeLabel,
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
        $typeId     = '279bb345-f7dd-4232-b9d8-acc5628e9fd3';
        $name       = 'Foo';
        $identifier = 'foo';
        $classes    = 'foo0 foo1';
        $protected  = false;
        $withLinks  = false;
        $withImage  = false;
        $withBody   = false;
        $withHtml   = false;
        $typeName   = 'L 123';
        $typeLabel  = 'l123';

        $sql          = 'SELECT lists.id, lists.type_id, lists.name, lists.identifier, lists.classes, lists.protected, lists.with_links, lists.with_image, lists.with_body, lists.with_html, list_types.name AS type_name, list_types.label AS type_label FROM lists INNER JOIN list_types AS list_types ON list_types.id = lists.type_id AND list_types.deleted_at IS NULL WHERE (lists.deleted_at IS NULL) AND (lists.identifier = :identifier)'; // phpcs:ignore
        $values       = ['identifier' => [$identifier, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'         => $id,
                'type_id'    => $typeId,
                'name'       => $name,
                'identifier' => $identifier,
                'classes'    => $classes,
                'protected'  => $protected,
                'with_links' => $withLinks,
                'with_image' => $withImage,
                'with_body'  => $withBody,
                'with_html'  => $withHtml,
                'type_name'  => $typeName,
                'type_label' => $typeLabel,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getByIdentifier($identifier);

        $this->assertEntity($expectedData[0], $actualResult);
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
     * @param string[] $expectedData
     * @param Entity   $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
        $this->assertInstanceOf(Entity::class, $entity);

        $this->assertSame($expectedData['id'], $entity->getId());
        $this->assertSame($expectedData['identifier'], $entity->getIdentifier());
        $this->assertSame($expectedData['classes'], $entity->getClasses());
        $this->assertSame($expectedData['protected'], $entity->isProtected());
        $this->assertSame($expectedData['with_image'], $entity->isWithImage());
        $this->assertSame($expectedData['with_links'], $entity->isWithLinks());
        $this->assertSame($expectedData['with_body'], $entity->isWithBody());
        $this->assertSame($expectedData['with_html'], $entity->isWithHtml());
    }
}
