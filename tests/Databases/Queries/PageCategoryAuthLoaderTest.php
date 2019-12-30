<?php

declare(strict_types=1);

namespace AbterPhp\Website\Databases\Queries;

use AbterPhp\Admin\Exception\Database;
use AbterPhp\Framework\TestCase\Database\QueryTestCase;
use AbterPhp\Framework\TestDouble\Database\MockStatementFactory;

class PageCategoryAuthLoaderTest extends QueryTestCase
{
    /** @var PageCategoryAuthLoader - System Under Test */
    protected $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new PageCategoryAuthLoader($this->connectionPoolMock);
    }

    public function testLoadAll()
    {
        $sql          = 'SELECT ug.identifier AS v0, pc.identifier AS v1 FROM user_groups_page_categories AS ugpc INNER JOIN page_categories AS pc ON ugpc.page_category_id = pc.id AND pc.deleted_at IS NULL INNER JOIN user_groups AS ug ON ugpc.user_group_id = ug.id AND ug.deleted_at IS NULL'; // phpcs:ignore
        $valuesToBind = [];
        $returnValue  = [
            ['v0' => 'foo', 'v1' => 'bar'],
            ['v0' => 'foo', 'v1' => 'baz'],
            ['v0' => 'qux', 'v1' => 'quux'],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $valuesToBind, $returnValue);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->loadAll();

        $this->assertSame($returnValue, $actualResult);
    }

    public function testLoadAllThrowsExceptionIfQueryFails()
    {
        $errorInfo   = ['FOO123', 1, 'near AS v0, ar.identifier: hello'];

        $this->expectException(Database::class);
        $this->expectExceptionCode($errorInfo[1]);

        $sql          = 'SELECT ug.identifier AS v0, pc.identifier AS v1 FROM user_groups_page_categories AS ugpc INNER JOIN page_categories AS pc ON ugpc.page_category_id = pc.id AND pc.deleted_at IS NULL INNER JOIN user_groups AS ug ON ugpc.user_group_id = ug.id AND ug.deleted_at IS NULL'; // phpcs:ignore
        $valuesToBind = [];
        $statement    = MockStatementFactory::createErrorStatement($this, $valuesToBind, $errorInfo);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $this->sut->loadAll();
    }
}
