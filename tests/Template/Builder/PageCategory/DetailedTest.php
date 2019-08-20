<?php

namespace AbterPhp\Website\Template\Builder\PageCategory;

use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\TestDouble\I18n\MockTranslatorFactory;
use AbterPhp\Website\Domain\Entities\Page;
use AbterPhp\Website\Domain\Entities\PageCategory;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Routing\Urls\UrlGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DetailedTest extends TestCase
{
    /** @var Detailed - System Under Test */
    protected $sut;

    /** @var IEventDispatcher|MockObject */
    protected $dispatcherMock;

    /** @var UrlGenerator|MockObject */
    protected $urlGeneratorMock;

    /** @var ITranslator|MockObject */
    protected $translatorMock;

    /** @var array */
    protected $translations = [];

    public function setUp(): void
    {
        $this->dispatcherMock = $this->getMockBuilder(IEventDispatcher::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['dispatch'])
            ->getMock();

        $this->urlGeneratorMock = $this->getMockBuilder(UrlGenerator::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createFromName', 'createRegexFromName'])
            ->getMock();

        $this->translatorMock = MockTranslatorFactory::createSimpleTranslator($this, $this->translations);

        $this->sut = new Detailed($this->dispatcherMock, $this->urlGeneratorMock, $this->translatorMock);
    }

    public function testBuildWithZeroPagesThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $pages = [];

        $this->sut->build($pages);
    }

    public function testBuildPageWithoutCategoryThrowsException()
    {
        $this->expectException(\LogicException::class);

        $page = new Page('', '', '', '', '', false, null);

        $this->sut->build([$page]);
    }

    public function testBuildOnePage()
    {
        $categoryIdentifier = 'foo';
        $pageIdentifier = 'bar';
        $pageTitle = 'Bar';

        $category = new PageCategory('', '', $categoryIdentifier);
        $page    = new Page('', $pageIdentifier, $pageTitle, '', '', false, $category);

        $actualResult = $this->sut->build([$page]);

        $this->assertSame($categoryIdentifier, $actualResult->getIdentifier());

        $templates = $actualResult->getTemplates();

        $this->assertIsArray($templates);
        $this->assertArrayHasKey('body', $templates);

        $body = $templates['body'];

        $this->assertStringContainsString($pageTitle, $body);
    }
}