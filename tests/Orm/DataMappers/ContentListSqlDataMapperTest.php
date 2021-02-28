<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMappers;

use AbterPhp\Admin\TestCase\Orm\DataMapperTestCase;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\TestDouble\Database\MockStatementFactory;
use AbterPhp\Website\Domain\Entities\ContentList as Entity;
use PHPUnit\Framework\MockObject\MockObject;

class ContentListSqlDataMapperTest extends DataMapperTestCase
{
    /** @var ContentListSqlDataMapper - System Under Test */
    protected $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new ContentListSqlDataMapper($this->readConnectionMock, $this->writeConnectionMock);
    }

    public function testAdd()
    {
        $nextId        = '9d160dd2-bd83-48f6-a3b0-e15d2f26e76c';
        $name          = 'Foo';
        $identifier    = 'foo';
        $classes       = 'foo0 foo1';
        $protected     = false;
        $withLinks     = false;
        $withNameLinks = false;
        $withImages    = false;
        $withHtml      = false;
        $withClasses   = false;

        $sql0       = 'INSERT INTO lists (id, name, identifier, classes, protected, with_links, with_label_links, with_html, with_images, with_classes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'; // phpcs:ignore
        $values     = [
            [$nextId, \PDO::PARAM_STR],
            [$name, \PDO::PARAM_STR],
            [$identifier, \PDO::PARAM_STR],
            [$classes, \PDO::PARAM_STR],
            [$protected, \PDO::PARAM_BOOL],
            [$withLinks, \PDO::PARAM_BOOL],
            [$withNameLinks, \PDO::PARAM_BOOL],
            [$withHtml, \PDO::PARAM_BOOL],
            [$withImages, \PDO::PARAM_BOOL],
            [$withClasses, \PDO::PARAM_BOOL],
        ];
        $statement0 = MockStatementFactory::createWriteStatement($this, $values);

        $this->writeConnectionMock
            ->expects($this->once())
            ->method('prepare')
            ->with($sql0)
            ->willReturn($statement0);

        $entity = new Entity(
            $nextId,
            $name,
            $identifier,
            $classes,
            $protected,
            $withLinks,
            $withNameLinks,
            $withImages,
            $withHtml,
            $withClasses
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

        $sql1       = 'UPDATE list_items AS list_items SET deleted_at = NOW() WHERE (list_id = ?) AND (deleted_at IS NOT NULL)'; // phpcs:ignore
        $values1    = [[$id, \PDO::PARAM_STR]];
        $statement1 = MockStatementFactory::createWriteStatement($this, $values1);

        $this->writeConnectionMock
            ->expects($this->exactly(2))
            ->method('prepare')
            ->withConsecutive([$sql0], [$sql1])
            ->willReturnOnConsecutiveCalls($statement0, $statement1);

        $entity = new Entity($id, '', '', '', false, false, false, false, false, false);

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id            = '8da63e49-5c76-4520-9280-30c125305239';
        $name          = 'Foo';
        $identifier    = 'foo';
        $classes       = 'foo0 foo1';
        $protected     = false;
        $withLinks     = false;
        $withNameLinks = false;
        $withImages    = false;
        $withHtml      = false;
        $withClasses   = false;

        $sql0         = 'SELECT lists.id, lists.name, lists.identifier, lists.classes, lists.protected, lists.with_links, lists.with_label_links, lists.with_html, lists.with_images, lists.with_classes FROM lists WHERE (lists.deleted_at IS NULL)'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'               => $id,
                'name'             => $name,
                'identifier'       => $identifier,
                'classes'          => $classes,
                'protected'        => $protected,
                'with_links'       => $withLinks,
                'with_label_links' => $withNameLinks,
                'with_images'      => $withImages,
                'with_html'        => $withHtml,
                'with_classes'     => $withClasses,
            ],
        ];
        $statement0   = MockStatementFactory::createReadStatement($this, $values, $expectedData);

        $this->readConnectionMock
            ->expects($this->once())
            ->method('prepare')
            ->with($sql0)
            ->willReturn($statement0);

        $actualResult = $this->sut->getAll();

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetPage()
    {
        $id            = '8da63e49-5c76-4520-9280-30c125305239';
        $name          = 'Foo';
        $identifier    = 'foo';
        $classes       = 'foo0 foo1';
        $protected     = false;
        $withLinks     = false;
        $withNameLinks = false;
        $withImages    = false;
        $withHtml      = false;
        $withClasses   = false;

        $sql0         = 'SELECT SQL_CALC_FOUND_ROWS lists.id, lists.name, lists.identifier, lists.classes, lists.protected, lists.with_links, lists.with_label_links, lists.with_html, lists.with_images, lists.with_classes FROM lists WHERE (lists.deleted_at IS NULL) ORDER BY name ASC LIMIT 10 OFFSET 0'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'               => $id,
                'name'             => $name,
                'identifier'       => $identifier,
                'classes'          => $classes,
                'protected'        => $protected,
                'with_links'       => $withLinks,
                'with_label_links' => $withNameLinks,
                'with_images'      => $withImages,
                'with_html'        => $withHtml,
                'with_classes'     => $withClasses,
            ],
        ];
        $statement0   = MockStatementFactory::createReadStatement($this, $values, $expectedData);

        $this->readConnectionMock
            ->expects($this->once())
            ->method('prepare')
            ->with($sql0)
            ->willReturn($statement0);

        $actualResult = $this->sut->getPage(0, 10, [], [], []);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetPageWithOrdersAndConditions()
    {
        $id            = '8da63e49-5c76-4520-9280-30c125305239';
        $name          = 'Foo';
        $identifier    = 'foo';
        $classes       = 'foo0 foo1';
        $protected     = false;
        $withLinks     = false;
        $withNameLinks = false;
        $withImages    = false;
        $withHtml      = false;
        $withClasses   = false;

        $orders     = ['lists.identifier ASC'];
        $conditions = ['lists.identifier LIKE \'abc%\'', 'lists.identifier LIKE \'%bca\''];

        $sql0         = 'SELECT SQL_CALC_FOUND_ROWS lists.id, lists.name, lists.identifier, lists.classes, lists.protected, lists.with_links, lists.with_label_links, lists.with_html, lists.with_images, lists.with_classes FROM lists WHERE (lists.deleted_at IS NULL) AND (lists.identifier LIKE \'abc%\') AND (lists.identifier LIKE \'%bca\') ORDER BY lists.identifier ASC LIMIT 10 OFFSET 0'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'               => $id,
                'name'             => $name,
                'identifier'       => $identifier,
                'classes'          => $classes,
                'protected'        => $protected,
                'with_links'       => $withLinks,
                'with_label_links' => $withNameLinks,
                'with_html'        => $withHtml,
                'with_images'      => $withImages,
                'with_classes'     => $withClasses,
            ],
        ];
        $statement0   = MockStatementFactory::createReadStatement($this, $values, $expectedData);

        $this->readConnectionMock
            ->expects($this->once())
            ->method('prepare')
            ->with($sql0)
            ->willReturn($statement0);

        $actualResult = $this->sut->getPage(0, 10, $orders, $conditions, []);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetById()
    {
        $id            = 'da406cd9-4a65-4384-b1dd-454c4d26c196';
        $name          = 'Foo';
        $identifier    = 'foo';
        $classes       = 'foo0 foo1';
        $protected     = false;
        $withLinks     = false;
        $withNameLinks = false;
        $withImages    = false;
        $withHtml      = false;
        $withClasses   = false;

        $sql0         = 'SELECT lists.id, lists.name, lists.identifier, lists.classes, lists.protected, lists.with_links, lists.with_label_links, lists.with_html, lists.with_images, lists.with_classes FROM lists WHERE (lists.deleted_at IS NULL) AND (lists.id = :id)'; // phpcs:ignore
        $values       = ['id' => [$id, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'               => $id,
                'name'             => $name,
                'identifier'       => $identifier,
                'classes'          => $classes,
                'protected'        => $protected,
                'with_links'       => $withLinks,
                'with_label_links' => $withNameLinks,
                'with_html'        => $withHtml,
                'with_images'      => $withImages,
                'with_classes'     => $withClasses,
            ],
        ];
        $statement0   = MockStatementFactory::createReadStatement($this, $values, $expectedData);

        $this->readConnectionMock
            ->expects($this->once())
            ->method('prepare')
            ->with($sql0)
            ->willReturn($statement0);

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetByIdentifier()
    {
        $id            = '42f019b3-5d49-4ee0-b785-63ef245a1ee0';
        $name          = 'Foo';
        $identifier    = 'foo';
        $classes       = 'foo0 foo1';
        $protected     = false;
        $withLinks     = false;
        $withNameLinks = false;
        $withImages    = false;
        $withHtml      = false;
        $withClasses   = false;

        $sql0         = 'SELECT lists.id, lists.name, lists.identifier, lists.classes, lists.protected, lists.with_links, lists.with_label_links, lists.with_html, lists.with_images, lists.with_classes FROM lists WHERE (lists.deleted_at IS NULL) AND (lists.identifier = :identifier)'; // phpcs:ignore
        $values       = ['identifier' => [$identifier, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'               => $id,
                'name'             => $name,
                'identifier'       => $identifier,
                'classes'          => $classes,
                'protected'        => $protected,
                'with_links'       => $withLinks,
                'with_label_links' => $withNameLinks,
                'with_html'        => $withHtml,
                'with_images'      => $withImages,
                'with_classes'     => $withClasses,
            ],
        ];
        $statement0   = MockStatementFactory::createReadStatement($this, $values, $expectedData);

        $this->readConnectionMock
            ->expects($this->once())
            ->method('prepare')
            ->with($sql0)
            ->willReturn($statement0);

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
        $this->assertSame($expectedData['with_links'], $entity->isWithLinks());
        $this->assertSame($expectedData['with_label_links'], $entity->isWithLabelLinks());
        $this->assertSame($expectedData['with_html'], $entity->isWithHtml());
        $this->assertSame($expectedData['with_images'], $entity->isWithImages());
        $this->assertSame($expectedData['with_classes'], $entity->isWithClasses());
    }
}
