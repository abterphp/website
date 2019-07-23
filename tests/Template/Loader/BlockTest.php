<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Loader;

use AbterPhp\Website\Databases\Queries\BlockCache as Cache;
use AbterPhp\Website\Domain\Entities\Block as Entity;
use AbterPhp\Website\Orm\BlockRepo as Repo;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class BlockTest extends \PHPUnit\Framework\TestCase
{
    /** @var Block */
    protected $sut;

    /** @var Repo|MockObject */
    protected $repo;

    /** @var Cache|MockObject */
    protected $cache;

    public function setUp()
    {
        $this->repo = $this->getMockBuilder(Repo::class)
            ->disableOriginalConstructor()
            ->setMethods(['getWithLayoutByIdentifiers'])
            ->getMock();

        $this->cache = $this->getMockBuilder(Cache::class)
            ->disableOriginalConstructor()
            ->setMethods(['hasAnyChangedSince'])
            ->getMock();

        $this->sut = new Block($this->repo, $this->cache);
    }

    public function testLoadOne()
    {
        $blockIdentifier = 'block-1';

        $entity = new Entity('', $blockIdentifier, '', '', '');

        $this->repo
            ->expects($this->any())
            ->method('getWithLayoutByIdentifiers')
            ->willReturn([$entity]);

        $templateDataCollection = $this->sut->load([$blockIdentifier]);

        $this->assertCount(1, $templateDataCollection);
        foreach ($templateDataCollection as $templateData) {
            $this->assertSame($blockIdentifier, $templateData->getIdentifier());
        }
    }
}
