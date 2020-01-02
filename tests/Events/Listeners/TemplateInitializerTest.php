<?php

declare(strict_types=1);

namespace AbterPhp\Website\Events\Listeners;

use AbterPhp\Framework\Events\TemplateEngineReady;
use AbterPhp\Framework\Template\Engine;
use AbterPhp\Framework\Template\Renderer;
use AbterPhp\Website\Template\Loader\Block as BlockLoader;
use AbterPhp\Website\Template\Loader\PageCategory as PageCategoryLoader;
use AbterPhp\Website\Template\Loader\ContentList as ContentListLoader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TemplateInitializerTest extends TestCase
{
    /** @var TemplateInitializer - System Under Test */
    protected $sut;

    /** @var BlockLoader|MockObject */
    protected $blockLoaderMock;

    /** @var PageCategoryLoader|MockObject */
    protected $pageCategoryLoaderMock;

    /** @var ContentListLoader|MockObject */
    protected $contentListLoader;

    public function setUp(): void
    {
        $this->blockLoaderMock        = $this->createMock(BlockLoader::class);
        $this->pageCategoryLoaderMock = $this->createMock(PageCategoryLoader::class);
        $this->contentListLoader = $this->createMock(ContentListLoader::class);

        $this->sut = new TemplateInitializer(
            $this->blockLoaderMock,
            $this->pageCategoryLoaderMock,
            $this->contentListLoader
        );
    }

    public function testHandle()
    {
        $rendererMock = $this->createMock(Renderer::class);
        $rendererMock
            ->expects($this->at(0))
            ->method('addLoader')
            ->with(TemplateInitializer::TEMPLATE_TYPE_BLOCK, $this->blockLoaderMock)
            ->willReturnSelf();
        $rendererMock
            ->expects($this->at(1))
            ->method('addLoader')
            ->with(TemplateInitializer::TEMPLATE_TYPE_PAGE_CATEGORY, $this->pageCategoryLoaderMock)
            ->willReturnSelf();
        $rendererMock
            ->expects($this->at(2))
            ->method('addLoader')
            ->with(TemplateInitializer::TEMPLATE_TYPE_LIST, $this->contentListLoader)
            ->willReturnSelf();

        $engineMock = $this->createMock(Engine::class);
        $engineMock->expects($this->atLeastOnce())->method('getRenderer')->willReturn($rendererMock);

        $event = new TemplateEngineReady($engineMock);

        $this->sut->handle($event);
    }
}
