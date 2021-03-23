<?php

declare(strict_types=1);

namespace AbterPhp\Website\Authorization;

use AbterPhp\Website\Databases\Queries\PageCategoryAuthLoader as AuthLoader;
use Casbin\Exceptions\CasbinException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PageCategoryProviderTest extends TestCase
{
    /** @var PageCategoryProvider */
    protected PageCategoryProvider $sut;

    /** @var AuthLoader|MockObject */
    protected $authLoaderMock;

    public function setUp(): void
    {
        $this->authLoaderMock = $this->createMock(AuthLoader::class);

        $this->sut = new PageCategoryProvider($this->authLoaderMock);
    }

    public function testRemoveFilterPolicyThrowsCasbinException()
    {
        $this->expectException(CasbinException::class);

        $this->sut->removeFilteredPolicy('foo', 'bar', 0);
    }
}
