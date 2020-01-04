<?php

namespace AbterPhp\Website\Template\Builder\ContentList;

class OrderedTest extends ContentListTest
{
    /** @var Ordered - System Under Test */
    protected $sut;

    public function setUp(): void
    {
        $this->sut = new Ordered();
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
<ol id="$identifier" class="ordered-list $typeName"><li><span class="item-name">Foo $itemId0</span></li>
<li><span class="item-name">Foo $itemId1</span></li>
</ol>
EOD;

        $withLinks = <<<EOD
<ol id="$identifier" class="ordered-list $typeName"><li><span class="item-name"><a href="/foo-$itemId0">Foo $itemId0</a></span></li>
<li><span class="item-name"><a href="/foo-$itemId1">Foo $itemId1</a></span></li>
</ol>
EOD;

        $withImage = <<<EOD
<ol id="$identifier" class="ordered-list $typeName"><li><span class="item-name">Foo $itemId0</span><span class="item-image"><img src="/baz0-$itemId0" alt="/baz1-$itemId0"></span></li>
<li><span class="item-name">Foo $itemId1</span><span class="item-image"><img src="/baz0-$itemId1" alt="/baz1-$itemId1"></span></li>
</ol>
EOD;

        $withBody = <<<EOD
<ol id="$identifier" class="ordered-list $typeName"><li><span class="item-name">Foo ${itemId0}</span><span class="item-body">Bar ${itemId0}</span></li>
<li><span class="item-name">Foo ${itemId1}</span><span class="item-body">Bar ${itemId1}</span></li>
</ol>
EOD;

        $withAll = <<<EOD
<ol id="$identifier" class="ordered-list $typeName"><li><span class="item-name"><a href="/foo-$itemId0">Foo $itemId0</a></span><span class="item-body"><a href="/bar-$itemId0">Bar $itemId0</a></span><span class="item-image"><a href="Baz $itemId0"><img src="/baz0-$itemId0" alt="/baz1-$itemId0"></a></span></li>
<li><span class="item-name"><a href="/foo-$itemId1">Foo $itemId1</a></span><span class="item-body"><a href="/bar-$itemId1">Bar $itemId1</a></span><span class="item-image"><a href="Baz $itemId1"><img src="/baz0-$itemId1" alt="/baz1-$itemId1"></a></span></li>
</ol>
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
