<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMapper;

use AbterPhp\Admin\TestCase\Orm\DataMapperTestCase;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\TestDouble\Database\MockStatementFactory;
use AbterPhp\Website\Domain\Entities\ContentListItem as Entity;
use AbterPhp\Website\Orm\DataMappers\ContentListItemSqlDataMapper as DataMapper;
use PHPUnit\Framework\MockObject\MockObject;

class ContentListItemSqlDataMapperTest extends DataMapperTestCase
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
        $nextId   = '9d160dd2-bd83-48f6-a3b0-e15d2f26e76c';
        $listId   = 'f95ffd21-eff5-4b10-a423-e222fb7fe56f';
        $name     = 'Foo';
        $nameHref = 'foo';
        $body     = 'Bar';
        $bodyHref = 'bar';
        $imgSrc   = 'baz';
        $imgAlt   = 'qux';
        $imgHref  = 'quix';

        $sql       = 'INSERT INTO list_items (id, list_id, name, name_href, body, body_href, img_src, img_alt, img_href) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'; // phpcs:ignore
        $values    = [
            [$nextId, \PDO::PARAM_STR],
            [$listId, \PDO::PARAM_STR],
            [$name, \PDO::PARAM_STR],
            [$nameHref, \PDO::PARAM_STR],
            [$body, \PDO::PARAM_STR],
            [$bodyHref, \PDO::PARAM_STR],
            [$imgSrc, \PDO::PARAM_STR],
            [$imgAlt, \PDO::PARAM_STR],
            [$imgHref, \PDO::PARAM_STR],
        ];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $entity = new Entity($nextId, $listId, $name, $nameHref, $body, $bodyHref, $imgSrc, $imgAlt, $imgHref);

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testDelete()
    {
        $id       = '9d160dd2-bd83-48f6-a3b0-e15d2f26e76c';
        $listId   = 'f95ffd21-eff5-4b10-a423-e222fb7fe56f';
        $name     = 'Foo';
        $nameHref = 'foo';
        $body     = 'Bar';
        $bodyHref = 'bar';
        $imgSrc   = 'baz';
        $imgHref  = 'qux';
        $imgAlt   = 'quix';

        $sql       = 'UPDATE list_items AS list_items SET deleted_at = NOW() WHERE (id = ?)'; // phpcs:ignore
        $values    = [[$id, \PDO::PARAM_STR]];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $entity = new Entity($id, $listId, $name, $nameHref, $body, $bodyHref, $imgSrc, $imgAlt, $imgHref);

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id       = '9d160dd2-bd83-48f6-a3b0-e15d2f26e76c';
        $listId   = 'f95ffd21-eff5-4b10-a423-e222fb7fe56f';
        $name     = 'Foo';
        $nameHref = 'foo';
        $body     = 'Bar';
        $bodyHref = 'bar';
        $imgSrc   = 'baz';
        $imgAlt   = 'qux';
        $imgHref  = 'quix';

        $sql          = 'SELECT list_items.id, list_items.list_id, list_items.name, list_items.name_href, list_items.body, list_items.body_href, list_items.img_src, list_items.img_alt, list_items.img_href FROM list_items WHERE (list_items.deleted_at IS NULL)'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'        => $id,
                'list_id'   => $listId,
                'name'      => $name,
                'name_href' => $nameHref,
                'body'      => $body,
                'body_href' => $bodyHref,
                'img_src'   => $imgSrc,
                'img_alt'   => $imgAlt,
                'img_href'  => $imgHref,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getAll();

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetPage()
    {
        $id       = '9d160dd2-bd83-48f6-a3b0-e15d2f26e76c';
        $listId   = 'f95ffd21-eff5-4b10-a423-e222fb7fe56f';
        $name     = 'Foo';
        $nameHref = 'foo';
        $body     = 'Bar';
        $bodyHref = 'bar';
        $imgSrc   = 'baz';
        $imgAlt   = 'qux';
        $imgHref  = 'quix';

        $sql          = 'SELECT SQL_CALC_FOUND_ROWS list_items.id, list_items.list_id, list_items.name, list_items.name_href, list_items.body, list_items.body_href, list_items.img_src, list_items.img_alt, list_items.img_href FROM list_items WHERE (list_items.deleted_at IS NULL) LIMIT 10 OFFSET 0'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'        => $id,
                'list_id'   => $listId,
                'name'      => $name,
                'name_href' => $nameHref,
                'body'      => $body,
                'body_href' => $bodyHref,
                'img_src'   => $imgSrc,
                'img_alt'   => $imgAlt,
                'img_href'  => $imgHref,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getPage(0, 10, [], [], []);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetById()
    {
        $id       = '9d160dd2-bd83-48f6-a3b0-e15d2f26e76c';
        $listId   = 'f95ffd21-eff5-4b10-a423-e222fb7fe56f';
        $name     = 'Foo';
        $nameHref = 'foo';
        $body     = 'Bar';
        $bodyHref = 'bar';
        $imgSrc   = 'baz';
        $imgAlt   = 'qux';
        $imgHref  = 'quix';

        $sql          = 'SELECT list_items.id, list_items.list_id, list_items.name, list_items.name_href, list_items.body, list_items.body_href, list_items.img_src, list_items.img_alt, list_items.img_href FROM list_items WHERE (list_items.deleted_at IS NULL) AND (list_items.id = :list_item_id)'; // phpcs:ignore
        $values       = ['list_item_id' => [$id, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'        => $id,
                'list_id'   => $listId,
                'name'      => $name,
                'name_href' => $nameHref,
                'body'      => $body,
                'body_href' => $bodyHref,
                'img_src'   => $imgSrc,
                'img_alt'   => $imgAlt,
                'img_href'  => $imgHref,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetByListId()
    {
        $id       = '9d160dd2-bd83-48f6-a3b0-e15d2f26e76c';
        $listId   = 'f95ffd21-eff5-4b10-a423-e222fb7fe56f';
        $name     = 'Foo';
        $nameHref = 'foo';
        $body     = 'Bar';
        $bodyHref = 'bar';
        $imgSrc   = 'baz';
        $imgAlt   = 'qux';
        $imgHref  = 'quix';

        $sql          = 'SELECT list_items.id, list_items.list_id, list_items.name, list_items.name_href, list_items.body, list_items.body_href, list_items.img_src, list_items.img_alt, list_items.img_href FROM list_items WHERE (list_items.deleted_at IS NULL) AND (list_items.list_id = :list_id)'; // phpcs:ignore
        $values       = ['list_id' => [$listId, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'        => $id,
                'list_id'   => $listId,
                'name'      => $name,
                'name_href' => $nameHref,
                'body'      => $body,
                'body_href' => $bodyHref,
                'img_src'   => $imgSrc,
                'img_alt'   => $imgAlt,
                'img_href'  => $imgHref,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getByListId($listId);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetByListIds()
    {
        $id       = '9d160dd2-bd83-48f6-a3b0-e15d2f26e76c';
        $listId0  = 'f95ffd21-eff5-4b10-a423-e222fb7fe56f';
        $name     = 'Foo';
        $nameHref = 'foo';
        $body     = 'Bar';
        $bodyHref = 'bar';
        $imgSrc   = 'baz';
        $imgAlt   = 'qux';
        $imgHref  = 'quix';

        $listId1 = '51ec9310-9a6d-4fdc-a26b-3a83dd373a18';

        $sql          = 'SELECT list_items.id, list_items.list_id, list_items.name, list_items.name_href, list_items.body, list_items.body_href, list_items.img_src, list_items.img_alt, list_items.img_href FROM list_items WHERE (list_items.deleted_at IS NULL) AND (list_items.list_id IN (?,?))'; // phpcs:ignore
        $values       = [[$listId0, \PDO::PARAM_STR], [$listId1, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'        => $id,
                'list_id'   => $listId0,
                'name'      => $name,
                'name_href' => $nameHref,
                'body'      => $body,
                'body_href' => $bodyHref,
                'img_src'   => $imgSrc,
                'img_alt'   => $imgAlt,
                'img_href'  => $imgHref,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getByListIds([$listId0, $listId1]);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testUpdate()
    {
        $id       = '9d160dd2-bd83-48f6-a3b0-e15d2f26e76c';
        $listId   = 'f95ffd21-eff5-4b10-a423-e222fb7fe56f';
        $name     = 'Foo';
        $nameHref = 'foo';
        $body     = 'Bar';
        $bodyHref = 'bar';
        $imgSrc   = 'baz';
        $imgAlt   = 'qux';
        $imgHref  = 'quix';

        $sql       = 'UPDATE list_items AS list_items SET list_id = ?, name = ?, name_href = ?, body = ?, body_href = ?, img_src = ?, img_alt = ?, img_href = ? WHERE (id = ?) AND (list_items.deleted_at IS NULL)'; // phpcs:ignore
        $values    = [
            [$listId, \PDO::PARAM_STR],
            [$name, \PDO::PARAM_STR],
            [$nameHref, \PDO::PARAM_STR],
            [$body, \PDO::PARAM_STR],
            [$bodyHref, \PDO::PARAM_STR],
            [$imgSrc, \PDO::PARAM_STR],
            [$imgAlt, \PDO::PARAM_STR],
            [$imgHref, \PDO::PARAM_STR],
            [$id, \PDO::PARAM_STR],
        ];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $entity = new Entity($id, $listId, $name, $nameHref, $body, $bodyHref, $imgSrc, $imgAlt, $imgHref);

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
        $this->assertSame($expectedData['list_id'], $entity->getListId());
        $this->assertSame($expectedData['name'], $entity->getName());
        $this->assertSame($expectedData['name_href'], $entity->getNameHref());
        $this->assertSame($expectedData['body'], $entity->getBody());
        $this->assertSame($expectedData['body_href'], $entity->getBodyHref());
        $this->assertSame($expectedData['img_src'], $entity->getImgSrc());
        $this->assertSame($expectedData['img_alt'], $entity->getImgAlt());
        $this->assertSame($expectedData['img_href'], $entity->getImgHref());
    }
}
