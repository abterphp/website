<?php

namespace AbterPhp\Website\Template\Builder\ContentList;

class HollowTest extends ContentListTest
{
    /** @var Hollow - System Under Test */
    protected $sut;

    public function setUp(): void
    {
        $this->sut = new Hollow();
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
<div id="$identifier" class="hollow $typeName">

</div>
EOD;

        $withLinksOnly = <<<EOD
<div id="$identifier" class="hollow $typeName">

</div>
EOD;

        $withImageOnly = <<<EOD
<div id="$identifier" class="hollow $typeName"><img src="/baz0-veowq" alt="/baz1-veowq">
<img src="/baz0-zzoel" alt="/baz1-zzoel">
</div>
EOD;

        $withBodyOnly = <<<EOD
<div id="$identifier" class="hollow $typeName">Bar veowq
Bar zzoel
</div>
EOD;

        $withAll = <<<EOD
<div id="$identifier" class="hollow $typeName"><a href="/bar-veowq">Bar veowq</a><a href="Baz veowq"><img src="/baz0-veowq" alt="/baz1-veowq"></a>
<a href="/bar-zzoel">Bar zzoel</a><a href="Baz zzoel"><img src="/baz0-zzoel" alt="/baz1-zzoel"></a>
</div>
EOD;

        // @codingStandardsIgnoreStart
        return [
            'with nothing'    => [$typeName, $identifier, false, false, false, $itemIds, $withNothing],
            'with links only' => [$typeName, $identifier, true, false, false, $itemIds, $withLinksOnly],
            'with image only' => [$typeName, $identifier, false, true, false, $itemIds, $withImageOnly],
            'with body only'  => [$typeName, $identifier, false, false, true, $itemIds, $withBodyOnly],
            'with all'        => [$typeName, $identifier, true, true, true, $itemIds, $withAll],
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
