<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory\Page;

use AbterPhp\Website\Domain\Entities\Page as Entity;
use PHPUnit\Framework\TestCase;

class MetaTest extends TestCase
{
    /** @var Meta - System Under Test */
    protected $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new Meta();
    }

    public function testCreate()
    {
        $id            = 'bde8a749-b409-43c6-a061-c6a7d2dce6a0';
        $identifier    = 'foo';
        $description   = '';
        $robots        = '';
        $author        = '';
        $copyright     = '';
        $keywords      = '';
        $oGTitle       = '';
        $ogImage       = '';
        $ogDescription = '';

        $meta = new Entity\Meta(
            $description,
            $robots,
            $author,
            $copyright,
            $keywords,
            $oGTitle,
            $ogImage,
            $ogDescription
        );
        $entity = new Entity($id, $identifier, '', '', '', '', false, null, '', null, $meta);

        $actualResult = $this->sut->create($entity);

        $this->assertIsArray($actualResult);

        $html = '';
        foreach ($actualResult as $item) {
            $html .= (string)$item;
        }
        $this->assertStringContainsString('description', $html);
        $this->assertStringContainsString('robots', $html);
        $this->assertStringContainsString('author', $html);
        $this->assertStringContainsString('copyright', $html);
        $this->assertStringContainsString('keywords', $html);
        $this->assertStringContainsString('og-title', $html);
        $this->assertStringContainsString('og-image', $html);
        $this->assertStringContainsString('og-description', $html);
    }
}
