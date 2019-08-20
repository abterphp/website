<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Loader;

use AbterPhp\Website\Databases\Queries\BlockCache as Cache;
use AbterPhp\Website\Domain\Entities\Block as Entity;
use AbterPhp\Website\Orm\BlockRepo as Repo;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BlockTest extends TestCase
{
    /** @var Block - System Under Test */
    protected $sut;

    /** @var Repo|MockObject */
    protected $repoMock;

    /** @var Cache|MockObject */
    protected $cacheMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->repoMock = $this->getMockBuilder(Repo::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getWithLayoutByIdentifiers'])
            ->getMock();

        $this->cacheMock = $this->getMockBuilder(Cache::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['hasAnyChangedSince'])
            ->getMock();

        $this->sut = new Block($this->repoMock, $this->cacheMock);
    }

    public function testLoadOne()
    {
        $blockIdentifier = 'block-1';

        $entity = new Entity('', $blockIdentifier, '', '', '');

        $this->repoMock
            ->expects($this->any())
            ->method('getWithLayoutByIdentifiers')
            ->willReturn([$entity]);

        $templateDataCollection = $this->sut->load([$blockIdentifier]);

        $this->assertCount(1, $templateDataCollection);
        foreach ($templateDataCollection as $templateData) {
            $this->assertSame($blockIdentifier, $templateData->getIdentifier());
        }
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
