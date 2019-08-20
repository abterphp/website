<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Loader;

use AbterPhp\Framework\Template\ParsedTemplate;
use AbterPhp\Website\Databases\Queries\BlockCache;
use AbterPhp\Website\Domain\Entities\Block as BlockEntity;
use AbterPhp\Website\Orm\BlockRepo;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BlockTest extends TestCase
{
    /** @var Block - System Under Test */
    protected $sut;

    /** @var BlockRepo|MockObject */
    protected $repoMock;

    /** @var BlockCache|MockObject */
    protected $cacheMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->repoMock = $this->createMock(BlockRepo::class);

        $this->cacheMock = $this->createMock(BlockCache::class);

        $this->sut = new Block($this->repoMock, $this->cacheMock);
    }

    public function testLoadOne()
    {
        $identifier = 'block-1';

        $entity = new BlockEntity('', $identifier, '', '', '');

        $this->repoMock
            ->expects($this->any())
            ->method('getWithLayoutByIdentifiers')
            ->willReturn([$entity]);

        $parsedTemplates = [
            $identifier => new ParsedTemplate('block', $identifier),
        ];

        $templateDataCollection = $this->sut->load($parsedTemplates);

        $this->assertCount(1, $templateDataCollection);
        foreach ($templateDataCollection as $templateData) {
            $this->assertSame($identifier, $templateData->getIdentifier());
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
