<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory;

use AbterPhp\Framework\Html\INode;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Website\Domain\Entities\PageLayout as Entity;
use Opulence\Http\Requests\RequestMethods;
use Opulence\Sessions\ISession;
use Opulence\Sessions\Session;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use AbterPhp\Website\Form\Factory\PageLayout\Assets as AssetsFactory;

class PageLayoutTest extends TestCase
{
    /** @var PageLayout - System Under Test */
    protected $sut;

    /** @var ISession|MockObject */
    protected $sessionMock;

    /** @var ITranslator|MockObject */
    protected $translatorMock;

    /** @var AssetsFactory|MockObject */
    protected $assetsFactoryMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->sessionMock = $this->createMock(Session::class);
        $this->sessionMock->expects($this->any())->method('get')->willReturnArgument(0);

        $this->translatorMock = $this->createMock(ITranslator::class);
        $this->translatorMock->expects($this->any())->method('translate')->willReturnArgument(0);

        $this->assetsFactoryMock = $this->createMock(AssetsFactory::class);

        $this->sut = new PageLayout($this->sessionMock, $this->translatorMock, $this->assetsFactoryMock);
    }

    public function testCreate()
    {
        $action     = 'foo';
        $method     = RequestMethods::POST;
        $showUrl    = 'bar';
        $entityId   = 'c5098ee4-ab53-4d96-9d23-bde122f8f09b';
        $identifier = 'blah';
        $body       = 'mah';

        $entityMock = $this->createMockEntity();

        $entityMock->expects($this->any())->method('getId')->willReturn($entityId);
        $entityMock->expects($this->any())->method('getIdentifier')->willReturn($identifier);
        $entityMock->expects($this->any())->method('getBody')->willReturn($body);

        $this->assetsFactoryMock->expects($this->any())->method('create')->willReturn([]);

        $form = (string)$this->sut->create($action, $method, $showUrl, $entityMock);

        $this->assertStringContainsString($action, $form);
        $this->assertStringContainsString($showUrl, $form);
        $this->assertStringContainsString('CSRF', $form);
        $this->assertStringContainsString('POST', $form);
        $this->assertStringContainsString('identifier', $form);
        $this->assertStringContainsString('body', $form);
        $this->assertStringContainsString('button', $form);
    }

    public function testCreateWithAssets()
    {
        $action     = 'foo';
        $method     = RequestMethods::POST;
        $showUrl    = 'bar';
        $entityId   = 'c5098ee4-ab53-4d96-9d23-bde122f8f09b';
        $identifier = 'blah';
        $body       = 'mah';

        $entityMock = $this->createMockEntity();

        $entityMock->expects($this->any())->method('getId')->willReturn($entityId);
        $entityMock->expects($this->any())->method('getIdentifier')->willReturn($identifier);
        $entityMock->expects($this->any())->method('getBody')->willReturn($body);

        $assetNodes = [
            $this->createMock(INode::class),
        ];
        $this->assetsFactoryMock->expects($this->any())->method('create')->willReturn($assetNodes);

        $form = (string)$this->sut->create($action, $method, $showUrl, $entityMock);

        $this->assertStringContainsString($action, $form);
        $this->assertStringContainsString($showUrl, $form);
        $this->assertStringContainsString('CSRF', $form);
        $this->assertStringContainsString('POST', $form);
        $this->assertStringContainsString('identifier', $form);
        $this->assertStringContainsString('body', $form);
        $this->assertStringContainsString('button', $form);
    }

    /**
     * @return MockObject|Entity
     */
    protected function createMockEntity()
    {
        $entityMock = $this->createMock(Entity::class);

        return $entityMock;
    }
}
