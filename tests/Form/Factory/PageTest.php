<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory;

use AbterPhp\Framework\Html\INode;
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
    /** @var Page - System Under Test */
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

    public function setUp(): void
    {
        parent::setUp();

        $this->sessionMock = $this->createMock(Session::class);
        $this->sessionMock->expects($this->any())->method('get')->willReturnArgument(0);

        $this->translatorMock = $this->createMock(ITranslator::class);
        $this->translatorMock->expects($this->any())->method('translate')->willReturnArgument(0);

        $categories = [new PageCategory('', '', '')];
        $this->categoryRepoMock = $this->createMock(PageCategoryRepo::class);
        $this->categoryRepoMock->expects($this->any())->method('getAll')->willReturn($categories);

        $this->layoutRepoMock = $this->createMock(PageLayoutRepo::class);

        $metaNodes = [$this->createMock(INode::class)];
        $this->metaFactoryMock = $this->createMock(MetaFactory::class);
        $this->metaFactoryMock->expects($this->any())->method('create')->willReturn($metaNodes);

        $assetNodes = [$this->createMock(INode::class)];
        $this->assetsFactoryMock = $this->createMock(AssetsFactory::class);
        $this->assetsFactoryMock->expects($this->any())->method('create')->willReturn($assetNodes);

        $this->enforcerMock = $this->createMock(Enforcer::class);

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
        $lead        = "blah tldr;";
        $body        = "Blah!\n\n...and more blah...";
        $isDraft     = false;
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

        /** @var Entity|MockObject $entityMock */
        $entityMock = $this->createMock(Entity::class);

        $entityMock->expects($this->any())->method('getId')->willReturn($entityId);
        $entityMock->expects($this->any())->method('getIdentifier')->willReturn($identifier);
        $entityMock->expects($this->any())->method('getTitle')->willReturn($title);
        $entityMock->expects($this->any())->method('getLead')->willReturn($lead);
        $entityMock->expects($this->any())->method('getBody')->willReturn($body);
        $entityMock->expects($this->any())->method('isDraft')->willReturn($isDraft);
        $entityMock->expects($this->any())->method('getCategory')->willReturn($category);
        $entityMock->expects($this->any())->method('getLayoutId')->willReturn($layoutId);
        $entityMock->expects($this->any())->method('getLayout')->willReturn($layout);
        $entityMock->expects($this->any())->method('getMeta')->willReturn($meta);

        $form = (string)$this->sut->create($action, $method, $showUrl, $entityMock);

        $this->assertStringContainsString($action, $form);
        $this->assertStringContainsString($showUrl, $form);
        foreach ($contains as $needle) {
            $this->assertStringContainsString($needle, $form);
        }
    }

    /**
     * @dataProvider createProvider
     *
     * @param bool  $advancedAllowed
     * @param array $contains
     */
    public function testCreateWithAssets(bool $advancedAllowed, array $contains)
    {
        $action      = 'foo';
        $method      = RequestMethods::POST;
        $showUrl     = 'bar';
        $entityId    = '619e5cd9-342e-4405-8d51-bf9a0ce944d1';
        $identifier  = 'blah';
        $title       = 'Blah!';
        $description = 'Blah and blah and more blah, but only reasonable amount of blah';
        $lead        = "blah tldr;";
        $body        = "Blah!\n\n...and more blah...";
        $isDraft     = false;
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

        /** @var Entity|MockObject $entityMock */
        $entityMock = $this->createMock(Entity::class);

        $entityMock->expects($this->any())->method('getId')->willReturn($entityId);
        $entityMock->expects($this->any())->method('getIdentifier')->willReturn($identifier);
        $entityMock->expects($this->any())->method('getTitle')->willReturn($title);
        $entityMock->expects($this->any())->method('getLead')->willReturn($lead);
        $entityMock->expects($this->any())->method('getBody')->willReturn($body);
        $entityMock->expects($this->any())->method('isDraft')->willReturn($isDraft);
        $entityMock->expects($this->any())->method('getCategory')->willReturn($category);
        $entityMock->expects($this->any())->method('getLayoutId')->willReturn($layoutId);
        $entityMock->expects($this->any())->method('getLayout')->willReturn($layout);
        $entityMock->expects($this->any())->method('getMeta')->willReturn($meta);

        $form = (string)$this->sut->create($action, $method, $showUrl, $entityMock);

        $this->assertStringContainsString($action, $form);
        $this->assertStringContainsString($showUrl, $form);
        foreach ($contains as $needle) {
            $this->assertStringContainsString($needle, $form);
        }
    }
}
