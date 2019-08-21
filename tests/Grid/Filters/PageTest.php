<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Filters;

use PHPUnit\Framework\TestCase;

class PageTest extends TestCase
{
    /**
     * @return array
     */
    public function filterProvider(): array
    {
        return [
            [[], [], null],
        ];
    }

    /**
     * @dataProvider filterProvider
     *
     * @param string[]    $intents
     * @param array       $attributes
     * @param string|null $tag
     */
    public function testFilter(array $intents, array $attributes, ?string $tag)
    {
        $sut = new Page($intents, $attributes, $tag);

        $html = (string)$sut;

        $this->assertStringContainsString('<div class="hidable">', $html);
        $this->assertStringContainsString('filter-identifier', $html);
        $this->assertStringContainsString('filter-title', $html);
        $this->assertStringContainsString('filter-lead', $html);
        $this->assertStringContainsString('filter-body', $html);
    }
}
