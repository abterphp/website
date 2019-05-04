<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory;

use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Website\Domain\Entities\Page as Entity;
use AbterPhp\Website\Domain\Entities\PageLayout;
use AbterPhp\Website\Domain\Entities\PageCategory;
use AbterPhp\Website\Form\Factory\Page\Assets as AssetsFactory;
use AbterPhp\Website\Form\Factory\Page\Meta as MetaFactory;
use AbterPhp\Website\Orm\PageCategoryRepo;
use AbterPhp\Website\Orm\PageLayoutRepo;
use Casbin\Enforcer;
use Opulence\Http\Requests\RequestMethods;
use Opulence\Sessions\ISession;
use Opulence\Sessions\Session;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PageTest extends TestCase
{
    /** @var Page */
    protected $sut;

    /** @var ISession|MockObject */
    protected $sessionMock;

    /** @var ITranslator|MockObject */
    protected $translatorMock;

    /** @var PageCategoryRepo|MockObject */
    protected $categoryRepoMock;

    /** @var PageLayoutRepo|MockObject */
    protected $layoutRepoMock;

    /** @var MetaFactory|MockObject */
    protected $metaFactoryMock;

    /** @var AssetsFactory|MockObject */
    protected $assetsFactoryMock;

    /** @var Enforcer|MockObject */
    protected $enforcerMock;

    public function setUp()
    {
        $this->sessionMock = $this->getMockBuilder(Session::class)
            ->setMethods(['get'])
            ->getMock();
        $this->sessionMock->expects($this->any())->method('get')->willReturnArgument(0);

        $this->translatorMock = $this->getMockBuilder(ITranslator::class)
            ->setMethods(['translate', 'canTranslate'])
            ->getMock();
        $this->translatorMock->expects($this->any())->method('translate')->willReturnArgument(0);

        $this->categoryRepoMock = $this->getMockBuilder(PageCategoryRepo::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAll'])
            ->getMock();

        $this->layoutRepoMock = $this->getMockBuilder(PageLayoutRepo::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAll'])
            ->getMock();

        $this->metaFactoryMock = $this->getMockBuilder(MetaFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->metaFactoryMock->expects($this->any())->method('create')->willReturn([]);

        $this->assetsFactoryMock = $this->getMockBuilder(AssetsFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->assetsFactoryMock->expects($this->any())->method('create')->willReturn([]);

        $this->enforcerMock = $this->getMockBuilder(Enforcer::class)
            ->disableOriginalConstructor()
            ->setMethods(['enforce'])
            ->getMock();

        $this->sut = new Page(
            $this->sessionMock,
            $this->translatorMock,
            $this->categoryRepoMock,
            $this->layoutRepoMock,
            $this->metaFactoryMock,
            $this->assetsFactoryMock,
            $this->enforcerMock
        );
    }

    /**
     * @return array
     */
    public function createProvider(): array
    {
        return [
            [
                false,
                [
                    'POST',
                    'CSRF',
                    'identifier',
                    'title',
                    'body',
                    'layout_id',
                    'layout',
                    'button',
                ],
            ],
            [
                true,
                [
                    'POST',
                    'CSRF',
                    'identifier',
                    'title',
                    'body',
                    'layout_id',
                    'layout',
                    'button',
                ],
            ],
        ];
    }

    /**
     * @dataProvider createProvider
     *
     * @param bool  $advancedAllowed
     * @param array $contains
     */
    public function testCreate(bool $advancedAllowed, array $contains)
    {
        $action      = 'foo';
        $method      = RequestMethods::POST;
        $showUrl     = 'bar';
        $entityId    = '619e5cd9-342e-4405-8d51-bf9a0ce944d1';
        $identifier  = 'blah';
        $title       = 'Blah!';
        $description = 'Blah and blah and more blah, but only reasonable amount of blah';
        $body        = "Blah!\n\n...and more blah...";
        $category    = new PageCategory('bb031692-7cb2-468b-9cfd-2a40136c5165', '', '');
        $layoutId    = '5131c135-185e-4342-9df2-969f57390287';
        $layout      = 'abc {{ var/body }} cba';
        $meta        = new Entity\Meta($description, '', '', '', '', '', '', '');

        $layouts = [
            new PageLayout('1ee4a806-724c-447b-951a-6594e6d12fbd', 'bl-126', 'BL 126', null),
            new PageLayout('bcd75cae-8837-4717-96fb-db09cab39ef4', 'bl-129', 'BL 129', null),
        ];

        $this->enforcerMock->expects($this->at(0))->method('enforce')->willReturn($advancedAllowed);
        $this->layoutRepoMock->expects($this->any())->method('getAll')->willReturn($layouts);

        $this->assetsFactoryMock->expects($this->any())->method('create')->willReturn([]);
        $this->metaFactoryMock->expects($this->any())->method('create')->willReturn([]);

        $entityMock = $this->createMockEntity();

        $entityMock->expects($this->any())->method('getId')->willReturn($entityId);
        $entityMock->expects($this->any())->method('getIdentifier')->willReturn($identifier);
        $entityMock->expects($this->any())->method('getTitle')->willReturn($title);
        $entityMock->expects($this->any())->method('getBody')->willReturn($body);
        $entityMock->expects($this->any())->method('getCategory')->willReturn($category);
        $entityMock->expects($this->any())->method('getLayoutId')->willReturn($layoutId);
        $entityMock->expects($this->any())->method('getLayout')->willReturn($layout);
        $entityMock->expects($this->any())->method('getMeta')->willReturn($meta);

        $form = (string)$this->sut->create($action, $method, $showUrl, $entityMock);

        $this->assertContains($action, $form);
        $this->assertContains($showUrl, $form);
        foreach ($contains as $needle) {
            $this->assertContains($needle, $form);
        }
    }

    /**
     * @return MockObject|Entity
     */
    protected function createMockEntity()
    {
        $entityMock = $this->getMockBuilder(Entity::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getId',
                    'getIdentifier',
                    'getTitle',
                    'getBody',
                    'getCategory',
                    'getLayoutId',
                    'getLayout',
                    'getMeta',
                ]
            )
            ->getMock();

        return $entityMock;
    }
}
