<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory\Page;

use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Website\Domain\Entities\Page as Entity;
use PHPUnit\Framework\TestCase;

class AssetsTest extends TestCase
{
    /** @var Assets - System Under Test */
    protected $sut;

    /** @var ITranslator */
    protected $translatorMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->translatorMock = $this->createMock(ITranslator::class);

        $this->sut = new Assets($this->translatorMock);
    }

    public function testCreate()
    {
        $id         = 'bde8a749-b409-43c6-a061-c6a7d2dce6a0';
        $identifier = 'foo';
        $header     = 'bar';
        $footer     = 'baz';
        $cssFiles   = ['qux', 'quux'];
        $jsFiles    = ['poic', 'yozz'];

        $assets = new Entity\Assets($identifier, $header, $footer, $cssFiles, $jsFiles, null);
        $entity = new Entity($id, $identifier, '', '', '', '', false, null, '', null, null, $assets);

        $actualResult = $this->sut->create($entity);

        $this->assertIsArray($actualResult);

        $html = '';
        foreach ($actualResult as $item) {
            $html .= (string)$item;
        }
        $this->assertStringContainsString('header', $html);
        $this->assertStringContainsString('footer', $html);
        $this->assertStringContainsString('css-files', $html);
        $this->assertStringContainsString('js-files', $html);
    }
}
