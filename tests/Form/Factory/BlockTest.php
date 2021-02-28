<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory;

use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Website\Domain\Entities\Block as Entity;
use AbterPhp\Website\Domain\Entities\BlockLayout;
use AbterPhp\Website\Orm\BlockLayoutRepo;
use Casbin\Enforcer;
use Opulence\Http\Requests\RequestMethods;
use Opulence\Sessions\ISession;
use Opulence\Sessions\Session;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BlockTest extends TestCase
{
    /** @var Block - System Under Test */
    protected $sut;

    /** @var ISession|MockObject */
    protected $sessionMock;

    /** @var ITranslator|MockObject */
    protected $translatorMock;

    /** @var BlockLayoutRepo|MockObject */
    protected $layoutRepoMock;

    /** @var Enforcer|MockObject */
    protected $enforcerMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->sessionMock = $this->createMock(Session::class);
        $this->sessionMock->expects($this->any())->method('get')->willReturnArgument(0);

        $this->translatorMock = $this->createMock(ITranslator::class);
        $this->translatorMock->expects($this->any())->method('translate')->willReturnArgument(0);

        $this->layoutRepoMock = $this->createMock(BlockLayoutRepo::class);

        $this->enforcerMock = $this->createMock(Enforcer::class);

        $this->sut = new Block($this->sessionMock, $this->translatorMock, $this->layoutRepoMock, $this->enforcerMock);
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
        $action     = 'foo';
        $method     = RequestMethods::POST;
        $showUrl    = 'bar';
        $entityId   = '4571f468-8d7a-4680-81b5-fb747abaf580';
        $identifier = 'blah';
        $title      = 'Blah!';
        $body       = "Blah!\n\n...and more blah...";
        $layoutId   = 'f1a8ba52-680c-4dc3-b399-84d77b6cdf18';
        $layout     = 'abc {{ var/body }} cba';

        $layouts = [
            new BlockLayout('5f480eb5-1a54-4f5c-8303-59ae466ada68', 'BL 126', 'bl-126', 'BL 126 B'),
            new BlockLayout('11325e40-1b6b-4820-8d4b-548a572acd02', 'BL 129', 'bl-129', 'BL 129 B'),
        ];

        $this->enforcerMock->expects($this->once())->method('enforce')->willReturn($advancedAllowed);
        $this->layoutRepoMock->expects($this->any())->method('getAll')->willReturn($layouts);

        /** @var Entity|MockObject $entityMock */
        $entityMock = $this->createMock(Entity::class);

        $entityMock->expects($this->any())->method('getId')->willReturn($entityId);
        $entityMock->expects($this->any())->method('getIdentifier')->willReturn($identifier);
        $entityMock->expects($this->any())->method('getTitle')->willReturn($title);
        $entityMock->expects($this->any())->method('getBody')->willReturn($body);
        $entityMock->expects($this->any())->method('getLayoutId')->willReturn($layoutId);
        $entityMock->expects($this->any())->method('getLayout')->willReturn($layout);

        $form = (string)$this->sut->create($action, $method, $showUrl, $entityMock);

        $this->assertStringContainsString($action, $form);
        $this->assertStringContainsString($showUrl, $form);
        foreach ($contains as $needle) {
            $this->assertStringContainsString($needle, $form);
        }
    }
}
