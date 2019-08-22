<?php

declare(strict_types=1);

namespace AbterPhp\Website\Events\Listeners;

use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Website\Domain\Entities\Page;
use AbterPhp\Website\Events\PageViewed;
use Casbin\Enforcer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DraftPageCheckerTest extends TestCase
{
    /** @var DraftPageChecker - System Under Test */
    protected $sut;

    /** @var Enforcer|MockObject */
    protected $enforcerMock;

    /** @var ITranslator|MockObject */
    protected $translatorMock;

    public function setUp(): void
    {
        $this->enforcerMock   = $this->createMock(Enforcer::class);
        $this->translatorMock = $this->createMock(ITranslator::class);

        $this->sut = new DraftPageChecker($this->enforcerMock, $this->translatorMock);
    }

    public function testHandleReturnsEarlyIfPageIsDraft()
    {
        $page = new Page('', '', '', '', '', false);

        $event = $this->createMock(PageViewed::class);
        $event->expects($this->once())->method('getPage')->willReturn($page);
        $event->expects($this->never())->method('getUserGroupIdentifiers');

        $this->sut->handle($event);
    }

    public function testHandleSetsIsAllowedForNonDraftPagesByDefault()
    {
        $page = new Page('', '', '', '', '', true);

        $event = $this->createMock(PageViewed::class);
        $event->expects($this->any())->method('getPage')->willReturn($page);
        $event->expects($this->any())->method('getUserGroupIdentifiers')->willReturn([]);

        $event->expects($this->once())->method('setIsNotAllowed');

        $this->sut->handle($event);
    }

    public function testHandleSetsIsNotAllowedForNonDraftPagesIfNoAllowedUserGroupIsFound()
    {
        $page          = new Page('', '', '', '', '', true);
        $ugIdentifiers = ['foo', 'bar'];

        $event = $this->createMock(PageViewed::class);
        $event->expects($this->any())->method('getPage')->willReturn($page);
        $event->expects($this->any())->method('getUserGroupIdentifiers')->willReturn($ugIdentifiers);

        $this->enforcerMock->expects($this->any())->method('enforce')->willReturn(false);

        $event->expects($this->once())->method('setIsNotAllowed');

        $this->sut->handle($event);
    }

    public function testHandleSetsIsAllowedForNonDraftPagesIfAllowedUserGroupIsFound()
    {
        $page          = new Page('', '', '', '', '', true);
        $ugIdentifiers = ['foo', 'bar'];

        $event = $this->createMock(PageViewed::class);
        $event->expects($this->any())->method('getPage')->willReturn($page);
        $event->expects($this->any())->method('getUserGroupIdentifiers')->willReturn($ugIdentifiers);

        $this->enforcerMock->expects($this->any())->method('enforce')->willReturn(true);

        $event->expects($this->once())->method('setIsAllowed');
        $event->expects($this->once())->method('setPage');

        $this->sut->handle($event);
    }
}
