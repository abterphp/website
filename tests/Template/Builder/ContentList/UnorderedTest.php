<?php

namespace AbterPhp\Website\Template\Builder\ContentList;

class UnorderedTest extends ContentListTest
{
    /** @var Unordered - System Under Test */
    protected $sut;

    public function setUp(): void
    {
        $this->sut = new Unordered();
    }

    /**
     * @return array
     */
    public function buildOneProvider(): array
    {
        $typeName   = 'siakwl';
        $identifier = 'isdfk';
        $itemId0    = 'veowq';
        $itemId1    = 'zzoel';
        $itemIds    = [$itemId0, $itemId1];

        // @codingStandardsIgnoreStart
        $withNothing = <<<EOD
<ul id="$identifier" class="unordered $typeName"><li><span class="item-name">Foo $itemId0</span></li>
<li><span class="item-name">Foo $itemId1</span></li>
</ul>
EOD;

        $withLinks = <<<EOD
<ul id="$identifier" class="unordered $typeName"><li><span class="item-name"><a href="/foo-$itemId0">Foo $itemId0</a></span></li>
<li><span class="item-name"><a href="/foo-$itemId1">Foo $itemId1</a></span></li>
</ul>
EOD;

        $withImage = <<<EOD
<ul id="$identifier" class="unordered $typeName"><li><span class="item-name">Foo $itemId0</span><span class="item-image"><img src="/baz0-$itemId0" alt="/baz1-$itemId0"></span></li>
<li><span class="item-name">Foo $itemId1</span><span class="item-image"><img src="/baz0-$itemId1" alt="/baz1-$itemId1"></span></li>
</ul>
EOD;

        $withBody = <<<EOD
<ul id="$identifier" class="unordered $typeName"><li><span class="item-name">Foo ${itemId0}</span><span class="item-body">Bar ${itemId0}</span></li>
<li><span class="item-name">Foo ${itemId1}</span><span class="item-body">Bar ${itemId1}</span></li>
</ul>
EOD;

        $withAll = <<<EOD
<ul id="$identifier" class="unordered $typeName"><li><span class="item-name"><a href="/foo-$itemId0">Foo $itemId0</a></span><span class="item-body"><a href="/bar-$itemId0">Bar $itemId0</a></span><span class="item-image"><a href="Baz $itemId0"><img src="/baz0-$itemId0" alt="/baz1-$itemId0"></a></span></li>
<li><span class="item-name"><a href="/foo-$itemId1">Foo $itemId1</a></span><span class="item-body"><a href="/bar-$itemId1">Bar $itemId1</a></span><span class="item-image"><a href="Baz $itemId1"><img src="/baz0-$itemId1" alt="/baz1-$itemId1"></a></span></li>
</ul>
EOD;
        // @codingStandardsIgnoreStart

        return [
            'with nothing' => [$typeName, $identifier, false, false, false, $itemIds, $withNothing],
            'with links'   => [$typeName, $identifier, true, false, false, $itemIds, $withLinks],
            'with image'   => [$typeName, $identifier, false, true, false, $itemIds, $withImage],
            'with body'    => [$typeName, $identifier, false, false, true, $itemIds, $withBody],
            'with all'     => [$typeName, $identifier, true, true, true, $itemIds, $withAll],
        ];
    }

    /**
     * @dataProvider buildOneProvider
     *
     * @param string   $typeName
     * @param string   $identifier
     * @param bool     $withLinks
     * @param bool     $withImage
     * @param bool     $withBody
     * @param string[] $itemIds
     * @param string   $expectedResult
     */
    public function testBuildOneList(
        string $typeName,
        string $identifier,
        bool $withLinks,
        bool $withImage,
        bool $withBody,
        array $itemIds,
        string $expectedResult
    ) {
        $entity = $this->createEntity($typeName, $identifier, ...$itemIds)
            ->setWithLinks($withLinks)
            ->setWithImage($withImage)
            ->setWithBody($withBody);

        $actualResult = $this->sut->build($entity);

        $this->assertSame($expectedResult, $actualResult->getTemplates()['body']);
    }
}
