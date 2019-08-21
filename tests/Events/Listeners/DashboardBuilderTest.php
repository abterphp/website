<?php

declare(strict_types=1);

namespace AbterPhp\Website\Events\Listeners;

use AbterPhp\Framework\Authorization\CombinedAdapter;
use AbterPhp\Framework\Dashboard\Dashboard;
use AbterPhp\Framework\Events\AuthReady;
use AbterPhp\Framework\Events\DashboardReady;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use AbterPhp\Website\Authorization\PageCategoryProvider as AuthProvider;

class DashboardBuilderTest extends TestCase
{
    /** @var DashboardBuilder - System Under Test */
    protected $sut;

    public function setUp(): void
    {
        $this->sut = new DashboardBuilder();
    }

    public function testHandle()
    {
        $dashboardMock = $this->createMock(Dashboard::class);
        $dashboardMock->expects($this->atLeastOnce())->method('offsetSet');

        $event = new DashboardReady($dashboardMock);

        $this->sut->handle($event);
    }
}
