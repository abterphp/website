<?php

declare(strict_types=1);

namespace AbterPhp\Website\Events\Listeners;

use AbterPhp\Framework\Events\EntityChange;
use AbterPhp\Framework\Template\CacheManager;
use AbterPhp\Website\Domain\Entities\Block;
use AbterPhp\Website\Domain\Entities\BlockLayout;
use AbterPhp\Website\Domain\Entities\Page;
use AbterPhp\Website\Domain\Entities\PageCategory;
use AbterPhp\Website\Domain\Entities\PageLayout;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PageInvalidatorTest extends TestCase
{
    /** @var PageInvalidator - System Under Test */
    protected $sut;

    /** @var CacheManager|MockObject */
    protected $cacheManagerMock;

    public function setUp(): void
    {
        $this->cacheManagerMock = $this->createMock(CacheManager::class);

        $this->sut = new PageInvalidator($this->cacheManagerMock);
    }

    /**
     * @return array
     */
    public function handleFlushesCacheManagerProvider(): array
    {
        return [
            [new EntityChange($this->createMock(Page::class), 'foo'), 1],
            [new EntityChange($this->createMock(PageLayout::class), 'foo'), 1],
            [new EntityChange($this->createMock(Block::class), 'foo'), 1],
            [new EntityChange($this->createMock(BlockLayout::class), 'foo'), 0],
            [new EntityChange($this->createMock(PageCategory::class), 'foo'), 0],
        ];
    }

    /**
     * @dataProvider handleFlushesCacheManagerProvider
     *
     * @param EntityChange $event
     * @param int          $flushCount
     */
    public function testHandleFlushesCacheManager(EntityChange $event, int $flushCount)
    {
        $this->cacheManagerMock->expects($this->exactly($flushCount))->method('flush');

        $this->sut->handle($event);
    }
}
