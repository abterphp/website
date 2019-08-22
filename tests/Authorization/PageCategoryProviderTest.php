<?php

declare(strict_types=1);

namespace AbterPhp\Website\Authorization;

use AbterPhp\Website\Databases\Queries\PageCategoryAuthLoader as AuthLoader;
use Casbin\Exceptions\CasbinException;
use Casbin\Model\Model;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PageCategoryProviderTest extends TestCase
{
    /** @var PageCategoryProvider */
    protected $sut;

    /** @var AuthLoader|MockObject */
    protected $authLoaderMock;

    public function setUp(): void
    {
        $this->authLoaderMock = $this->createMock(AuthLoader::class);

        $this->sut = new PageCategoryProvider($this->authLoaderMock);
    }

    public function testSavePolicyReturnsTrue()
    {
        $modelStub = $this->createMock(Model::class);

        $actualResult = $this->sut->savePolicy($modelStub);

        $this->assertTrue($actualResult);
    }

    public function testAddPolicyDoesNotThrowException()
    {
        $actualResult = $this->sut->addPolicy('foo', 'bar', []);

        $this->assertNull($actualResult);
    }

    public function testRemovePolicyReturnZero()
    {
        $actualResult = $this->sut->removePolicy('foo', 'bar', []);

        $this->assertSame(0, $actualResult);
    }

    public function testRemoveFilterPolicyThrowsCasbinException()
    {
        $this->expectException(CasbinException::class);

        $this->sut->removeFilteredPolicy('foo', 'bar', 'baz');
    }
}
