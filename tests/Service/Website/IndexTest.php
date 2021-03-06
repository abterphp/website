<?php

declare(strict_types=1);

namespace AbterPhp\Website\Service\Website;

use AbterPhp\Admin\Domain\Entities\User;
use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Admin\Domain\Entities\UserLanguage;
use AbterPhp\Admin\Orm\UserRepo;
use AbterPhp\Framework\Template\Engine;
use AbterPhp\Website\Domain\Entities\Page;
use AbterPhp\Website\Orm\PageRepo;
use Casbin\Exceptions\CasbinException;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Orm\OrmException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    /** @var Index - System Under Test */
    protected $sut;

    /** @var Engine|MockObject */
    protected $engineMock;

    /** @var PageRepo|MockObject */
    protected $pageRepoMock;

    /** @var UserRepo|MockObject */
    protected $userRepoMock;

    /** @var IEventDispatcher|MockObject */
    protected $eventDispatcherMock;

    public function setUp(): void
    {
        $this->engineMock          = $this->createMock(Engine::class);
        $this->pageRepoMock        = $this->createMock(PageRepo::class);
        $this->userRepoMock        = $this->createMock(UserRepo::class);
        $this->eventDispatcherMock = $this->createMock(IEventDispatcher::class);

        $this->sut = new Index(
            $this->engineMock,
            $this->pageRepoMock,
            $this->userRepoMock,
            $this->eventDispatcherMock
        );
    }

    public function testGetRenderedPageThrowsOrmExceptionIfEntityIsNotFound()
    {
        $this->expectException(OrmException::class);

        $identifier           = 'foo';
        $userGroupIdentifiers = [];

        $this->pageRepoMock->expects($this->any())->method('getWithLayout')->willThrowException(new OrmException());

        $this->sut->getRenderedPage($identifier, $userGroupIdentifiers);
    }

    public function testGetRenderedPageReturnsNullByDefaultIfPageIsDraft()
    {
        $this->expectException(CasbinException::class);

        $identifier           = 'foo';
        $userGroupIdentifiers = [];

        $entity = new Page('', '', '', '', '', '', true, null, '', null);

        $this->pageRepoMock->expects($this->any())->method('getWithLayout')->willReturn($entity);

        $this->sut->getRenderedPage($identifier, $userGroupIdentifiers);
    }

    public function testGetRenderedPageRendersPageIfPageIsVisible()
    {
        $identifier           = 'foo';
        $userGroupIdentifiers = [];
        $renderedBody         = 'bar';

        $entity = new Page('', '', '', '', '', '', false, null, '', null);

        $this->pageRepoMock->expects($this->any())->method('getWithLayout')->willReturn($entity);

        $this->engineMock->expects($this->atLeastOnce())->method('run')->willReturn($renderedBody);

        $actualResult = $this->sut->getRenderedPage($identifier, $userGroupIdentifiers);

        $this->assertInstanceOf(Page::class, $actualResult);
        $this->assertSame($renderedBody, $actualResult->getRenderedBody());
    }

    public function testGetUserGroupIdentifiers()
    {
        $visitorUsername = 'foo';

        $userGroup0 = new UserGroup('', '', '', []);
        $userGroup1 = new UserGroup('', '', '', []);
        $userGroups = [$userGroup0, $userGroup1];

        $language = new UserLanguage('', '', '');
        $user     = new User('', $visitorUsername, '', '', false, false, $language, $userGroups);

        $this->userRepoMock->expects($this->any())->method('getByUsername')->willReturn($user);

        $actualResult = $this->sut->getUserGroupIdentifiers($visitorUsername);

        $this->assertIsArray($actualResult);

        foreach ($actualResult as $idx => $item) {
            $this->assertArrayHasKey($idx, $userGroups);
            $this->assertSame($userGroups[$idx]->getIdentifier(), $item);
        }
    }

    public function testGetUserGroupIdentifiersReturnsEarlyIfEntityIsNotFound()
    {
        $visitorUsername = 'foo';

        $this->userRepoMock->expects($this->any())->method('getByUsername')->willThrowException(new OrmException());

        $actualResult = $this->sut->getUserGroupIdentifiers($visitorUsername);

        $this->assertSame([], $actualResult);
    }
}
