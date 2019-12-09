<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory\PageLayout;

use AbterPhp\Website\Domain\Entities\PageLayout as Entity;
use PHPUnit\Framework\TestCase;

class AssetsTest extends TestCase
{
    /** @var Assets - System Under Test */
    protected $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new Assets();
    }

    public function testCreate()
    {
        $id         = 'bde8a749-b409-43c6-a061-c6a7d2dce6a0';
        $name       = 'Foo';
        $identifier = 'foo';
        $header     = 'bar';
        $footer     = 'baz';
        $cssFiles   = ['qux', 'quux'];
        $jsFiles    = ['poic', 'yozz'];
        $body       = 'pior';

        $assets = new Entity\Assets($identifier, $header, $footer, $cssFiles, $jsFiles);
        $entity = new Entity($id, $name, $identifier, $body, $assets);

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
