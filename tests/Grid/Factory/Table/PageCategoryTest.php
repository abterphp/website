<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory\Table;

use AbterPhp\Admin\Grid\Factory\Table\BodyFactory;
use AbterPhp\Framework\Grid\Table\Table;
use AbterPhp\Website\Grid\Factory\Table\Header\PageCategory as HeaderFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PageCategoryTest extends TestCase
{
    /** @var PageCategory - System Under Test */
    protected $sut;

    /** @var MockObject|HeaderFactory */
    protected $headerFactoryMock;

    /** @var MockObject|BodyFactory */
    protected $bodyFactoryMock;

    public function setUp(): void
    {
        $this->headerFactoryMock = $this->createMock(HeaderFactory::class);
        $this->bodyFactoryMock   = $this->createMock(BodyFactory::class);

        $this->sut = new PageCategory($this->headerFactoryMock, $this->bodyFactoryMock);
    }

    public function testCreate()
    {
        $getters    = [];
        $rowActions = null;
        $params     = [];
        $baseUrl    = '';

        $actualResult = $this->sut->create($getters, $rowActions, $params, $baseUrl);

        $this->assertInstanceOf(Table::class, $actualResult);
    }
}
