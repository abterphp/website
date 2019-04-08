<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template;

use AbterPhp\Website\Databases\Queries\BlockCache;
use AbterPhp\Website\Domain\Entities\Block;
use AbterPhp\Website\Orm\BlockRepo;
use AbterPhp\Website\Template\BlockLoader;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class BlockLoaderTest extends \PHPUnit\Framework\TestCase
{
    /** @var BlockLoader */
    protected $sut;

    /** @var BlockRepo|MockObject */
    protected $blockRepo;

    /** @var BlockCache|MockObject */
    protected $blockCache;

    public function setUp()
    {
        $this->blockRepo = $this->getMockBuilder(BlockRepo::class)
            ->disableOriginalConstructor()
            ->setMethods(['getWithLayoutByIdentifiers'])
            ->getMock();

        $this->blockCache = $this->getMockBuilder(BlockCache::class)
            ->disableOriginalConstructor()
            ->setMethods(['hasAnyChangedSince'])
            ->getMock();

        $this->sut = new BlockLoader($this->blockRepo, $this->blockCache);
    }

    public function testLoadOne()
    {
        $blockIdentifier = 'block-1';

        $block = new Block('', $blockIdentifier, '', '', '');

        $this->blockRepo
            ->expects($this->any())
            ->method('getWithLayoutByIdentifiers')
            ->willReturn([$block]);

        $templateDataCollection = $this->sut->load([$blockIdentifier]);

        $this->assertCount(1, $templateDataCollection);
        foreach ($templateDataCollection as $templateData) {
            $this->assertSame($blockIdentifier, $templateData->getIdentifier());
        }
    }
}
