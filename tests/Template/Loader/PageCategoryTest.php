<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Loader;

use AbterPhp\Framework\Template\IBuilder;
use AbterPhp\Framework\Template\IData;
use AbterPhp\Framework\Template\ParsedTemplate;
use AbterPhp\Website\Databases\Queries\PageCategoryCache;
use AbterPhp\Website\Domain\Entities\Page;
use AbterPhp\Website\Domain\Entities\PageCategory as PageCategoryEntity;
use AbterPhp\Website\Orm\PageRepo;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PageCategoryTest extends TestCase
{
    /** @var PageCategory - System Under Test */
    protected $sut;

    /** @var PageRepo|MockObject */
    protected $pageRepoMock;

    /** @var PageCategoryCache|MockObject */
    protected $cacheMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->pageRepoMock = $this->getMockBuilder(PageRepo::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByCategoryIdentifiers'])
            ->getMock();

        $this->cacheMock = $this->getMockBuilder(PageCategoryCache::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['hasAnyChangedSince'])
            ->getMock();

        $this->sut = new PageCategory($this->pageRepoMock, $this->cacheMock, []);
    }

    public function testLoadZero()
    {
        $identifier = 'pc-1';

        $category = new PageCategoryEntity('', 'PC #1', $identifier);
        $page     = new Page('', $identifier, '', '', '', false, $category);

        $this->pageRepoMock
            ->expects($this->any())
            ->method('getByCategoryIdentifiers')
            ->willReturn([$page]);

        $parsedTemplates = [];

        $actualResult = $this->sut->load($parsedTemplates);

        $this->assertEquals([], $actualResult);
    }

    public function testLoadOne()
    {
        $identifier = 'pc-1';

        $category = new PageCategoryEntity('', 'PC #1', $identifier);
        $page     = new Page('', $identifier, '', '', '', false, $category);

        $this->pageRepoMock
            ->expects($this->any())
            ->method('getByCategoryIdentifiers')
            ->willReturn([$page]);

        $dataStub = $this->getMockBuilder(IData::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getIdentifier', 'getTemplates', 'getVars'])
            ->getMock();

        /** @var IBuilder|MockObject $builderMock */
        $builderMock = $this->getMockBuilder(IBuilder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['build', 'getIdentifier'])
            ->getMock();
        $builderMock->expects($this->once())->method('build')->willReturn($dataStub);

        $this->sut->addBuilder('detailed', $builderMock);

        $parsedTemplates = [
            $identifier => [
                new ParsedTemplate('pagecategory', $identifier, ['builder' => 'detailed']),
            ],
        ];

        $actualResult = $this->sut->load($parsedTemplates);

        $this->assertEquals([$dataStub], $actualResult);
    }

    public function testLoadMultiple()
    {
        $identifier0 = 'pc-1';
        $identifier1 = 'pc-2';

        $category0 = new PageCategoryEntity('', 'PC #0', $identifier0);
        $page00    = new Page('', $identifier0, '', '', '', false, $category0);
        $page01    = new Page('', $identifier0, '', '', '', false, $category0);
        $category1 = new PageCategoryEntity('', 'PC #1', $identifier1);
        $page10    = new Page('', $identifier1, '', '', '', false, $category1);

        $this->pageRepoMock
            ->expects($this->any())
            ->method('getByCategoryIdentifiers')
            ->willReturn([$page00, $page01, $page10]);

        $dataStub = $this->getMockBuilder(IData::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getIdentifier', 'getTemplates', 'getVars'])
            ->getMock();

        /** @var IBuilder|MockObject $builderMock */
        $builderMock0 = $this->getMockBuilder(IBuilder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['build', 'getIdentifier'])
            ->getMock();
        $builderMock0->expects($this->exactly(2))->method('build')->willReturn($dataStub);
        $builderMock1 = $this->getMockBuilder(IBuilder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['build', 'getIdentifier'])
            ->getMock();
        $builderMock1->expects($this->once())->method('build')->willReturn($dataStub);

        $this->sut->addBuilder('detailed', $builderMock0)->addBuilder('simple', $builderMock1);

        $parsedTemplates = [
            $identifier0 => [
                new ParsedTemplate('pagecategory', $identifier0, ['builder' => 'detailed']),
                new ParsedTemplate('pagecategory', $identifier1, ['builder' => 'simple']),
            ],
            $identifier1 => [
                new ParsedTemplate('pagecategory', $identifier1, ['builder' => 'detailed']),
            ],
        ];

        $actualResult = $this->sut->load($parsedTemplates);

        $this->assertEquals([$dataStub, $dataStub, $dataStub], $actualResult);
    }

    public function testHasAnyChangedSinceCallsBlockCache()
    {
        $identifiers    = ['foo', 'bar', 'baz'];
        $cacheTime      = 'foo';
        $expectedResult = true;

        $this->cacheMock
            ->expects($this->once())
            ->method('hasAnyChangedSince')
            ->with($identifiers, $cacheTime)
            ->willReturn($expectedResult);

        $actualResult = $this->sut->hasAnyChangedSince($identifiers, $cacheTime);

        $this->assertSame($expectedResult, $actualResult);
    }
}
