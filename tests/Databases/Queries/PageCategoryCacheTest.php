<?php

declare(strict_types=1);

namespace AbterPhp\Website\Databases\Queries;

use AbterPhp\Admin\Exception\Database;
use AbterPhp\Framework\TestCase\Database\QueryTestCase;
use AbterPhp\Framework\TestDouble\Database\MockStatementFactory;

class PageCategoryCacheTest extends QueryTestCase
{
    /** @var PageCategoryCache - System Under Test */
    protected $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new PageCategoryCache($this->connectionPoolMock);
    }

    public function testHasAnyChangedSinceReturnsFalseIfNothingHasChanged()
    {
        $identifiers = ['foo', 'bar'];
        $cacheTime   = 'baz';

        $sql          = 'SELECT COUNT(*) AS count FROM pages LEFT JOIN page_categories AS page_categories ON page_categories.id = pages.category_id WHERE (pages.deleted = 0) AND (page_categories.identifier IN (?,?)) AND (pages.updated_at > ?)'; // phpcs:ignore
        $valuesToBind = [
            [$identifiers[0], \PDO::PARAM_STR],
            [$identifiers[1], \PDO::PARAM_STR],
            [$cacheTime, \PDO::PARAM_STR],
        ];
        $returnValue  = '0';
        $statement    = MockStatementFactory::createReadColumnStatement($this, $valuesToBind, $returnValue);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->hasAnyChangedSince($identifiers, $cacheTime);

        $this->assertFalse($actualResult);
    }

    public function testHasAnyChangedSinceReturnsTrueIfSomeBlocksHaveChanged()
    {
        $identifiers = ['foo', 'bar'];
        $cacheTime   = 'baz';

        $sql          = 'SELECT COUNT(*) AS count FROM pages LEFT JOIN page_categories AS page_categories ON page_categories.id = pages.category_id WHERE (pages.deleted = 0) AND (page_categories.identifier IN (?,?)) AND (pages.updated_at > ?)'; // phpcs:ignore
        $valuesToBind = [
            [$identifiers[0], \PDO::PARAM_STR],
            [$identifiers[1], \PDO::PARAM_STR],
            [$cacheTime, \PDO::PARAM_STR],
        ];
        $returnValue  = '2';
        $statement    = MockStatementFactory::createReadColumnStatement($this, $valuesToBind, $returnValue);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->hasAnyChangedSince($identifiers, $cacheTime);

        $this->assertTrue($actualResult);
    }

    public function testHasAnyChangedSinceThrowsExceptionIfQueryFails()
    {
        $identifiers = ['foo', 'bar'];
        $cacheTime   = 'baz';
        $errorInfo   = ['FOO123', 1, 'near AS v0, ar.identifier: hello'];

        $this->expectException(Database::class);
        $this->expectExceptionCode($errorInfo[1]);

        $sql          = 'SELECT COUNT(*) AS count FROM pages LEFT JOIN page_categories AS page_categories ON page_categories.id = pages.category_id WHERE (pages.deleted = 0) AND (page_categories.identifier IN (?,?)) AND (pages.updated_at > ?)'; // phpcs:ignore
        $valuesToBind = [
            [$identifiers[0], \PDO::PARAM_STR],
            [$identifiers[1], \PDO::PARAM_STR],
            [$cacheTime, \PDO::PARAM_STR],
        ];
        $statement    = MockStatementFactory::createErrorStatement($this, $valuesToBind, $errorInfo);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $this->sut->hasAnyChangedSince($identifiers, $cacheTime);
    }
}
