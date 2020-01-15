<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory;

use AbterPhp\Admin\Grid\Factory\GridFactory;
use AbterPhp\Admin\Grid\Factory\PaginationFactory;
use AbterPhp\Framework\Grid\IGrid;
use AbterPhp\Website\Domain\Entities\Page as Entity;
use AbterPhp\Website\Domain\Entities\PageCategory;
use AbterPhp\Website\Grid\Factory\Table\Page as TableFactory;
use AbterPhp\Website\Grid\Filters\Page as Filters;
use Opulence\Routing\Urls\UrlGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PageTest extends TestCase
{
    /** @var Page - System Under Test */
    protected $sut;

    /** @var MockObject|UrlGenerator */
    protected $urlGeneratorMock;

    /** @var MockObject|PaginationFactory */
    protected $paginationFactoryMock;

    /** @var MockObject|TableFactory */
    protected $tableFactoryMock;

    /** @var MockObject|GridFactory */
    protected $gridFactoryMock;

    /** @var MockObject|Filters */
    protected $filtersMock;

    public function setUp(): void
    {
        $this->urlGeneratorMock      = $this->createMock(UrlGenerator::class);
        $this->paginationFactoryMock = $this->createMock(PaginationFactory::class);
        $this->tableFactoryMock      = $this->createMock(TableFactory::class);
        $this->gridFactoryMock       = $this->createMock(GridFactory::class);
        $this->filtersMock           = $this->createMock(Filters::class);

        $this->sut = new Page(
            $this->urlGeneratorMock,
            $this->paginationFactoryMock,
            $this->tableFactoryMock,
            $this->gridFactoryMock,
            $this->filtersMock
        );
    }

    public function testCreateGrid()
    {
        $params  = [];
        $baseUrl = '';

        $actualResult = $this->sut->createGrid($params, $baseUrl);

        $this->assertInstanceOf(IGrid::class, $actualResult);
    }

    public function testGetCategoryNameIsEmptyStringIfNoCategoryIsSet()
    {
        $entity = new Entity('', '', '', '', '', '', false);

        $actualResult = $this->sut->getCategoryName($entity);

        $this->assertSame('<i class="material-icons pmd-sm">remove</i>', $actualResult);
    }

    public function testGetCategoryReturnsCategoryNameIfSet()
    {
        $categoryName = 'foo';
        $category     = new PageCategory('', $categoryName, '');
        $entity       = new Entity('', '', '', '', '', '', false, $category);

        $actualResult = $this->sut->getCategoryName($entity);

        $this->assertSame($categoryName, $actualResult);
    }

    public function testIsPublishedIfPageIsDraft()
    {
        $entity = new Entity('', '', '', '', '', '', true);

        $actualResult = $this->sut->isPublished($entity);

        $this->assertStringContainsString('is-danger', $actualResult);
    }

    public function testIsPublishedIfPageIsNotDraft()
    {
        $entity = new Entity('', '', '', '', '', '', false);

        $actualResult = $this->sut->isPublished($entity);

        $this->assertStringContainsString('is-success', $actualResult);
    }
}
