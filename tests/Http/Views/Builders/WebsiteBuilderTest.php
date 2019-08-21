<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Views\Builders;

use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Views\IView;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class WebsiteBuilderTest extends TestCase
{
    /** @var WebsiteBuilder - System Under Test */
    protected $sut;

    /** @var MockObject|IEventDispatcher */
    protected $eventDispatcherMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->eventDispatcherMock = $this->createMock(IEventDispatcher::class);

        $this->sut = new WebsiteBuilder($this->eventDispatcherMock);
    }

    public function testBuild()
    {
        /** @var IView|MockObject $viewMock */
        $viewMock = $this->createMock(IView::class);

        $this->eventDispatcherMock->expects($this->atLeastOnce())->method('dispatch');

        $actualResult = $this->sut->build($viewMock);

        $this->assertSame($viewMock, $actualResult);
    }
}
