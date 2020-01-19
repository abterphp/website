<?php

namespace AbterPhp\Website\Template\Builder\ContentList;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Template\ParsedTemplate;

class DefinitionListTest extends ContentListTest
{
    /** @var DefinitionList - System Under Test */
    protected $sut;

    public function setUp(): void
    {
        $this->sut = new DefinitionList();
    }

    /**
     * @return array
     */
    public function buildDefaultProvider(): array
    {
        $identifier = 'isdfk';
        $itemId0    = 'veowq';
        $itemId1    = 'zzoel';
        $itemIds    = [$itemId0, $itemId1];

        // @codingStandardsIgnoreStart
        $withNothing = <<<EOD
<dl id="$identifier" class="definition-list"><dt class="list-item-label">Foo $itemId0</dt><dd class="list-item-content">Bar $itemId0</dd>
<dt class="list-item-label">Foo $itemId1</dt><dd class="list-item-content">Bar $itemId1</dd></dl>
EOD;

        $withLinks = <<<EOD
<dl id="$identifier" class="definition-list"><dt class="list-item-label"><a href="/bar-$itemId0">Foo $itemId0</a></dt><dd class="list-item-content"><a href="/bar-$itemId0">Bar $itemId0</a></dd>
<dt class="list-item-label"><a href="/bar-$itemId1">Foo $itemId1</a></dt><dd class="list-item-content"><a href="/bar-$itemId1">Bar $itemId1</a></dd></dl>
EOD;

        $withLabelLinks = <<<EOD
<dl id="$identifier" class="definition-list"><dt class="list-item-label"><a href="/foo-$itemId0">Foo $itemId0</a></dt><dd class="list-item-content"><a href="/bar-$itemId0">Bar $itemId0</a></dd>
<dt class="list-item-label"><a href="/foo-$itemId1">Foo $itemId1</a></dt><dd class="list-item-content"><a href="/bar-$itemId1">Bar $itemId1</a></dd></dl>
EOD;

        $withImage = <<<EOD
<dl id="$identifier" class="definition-list"><dt class="list-item-label">Foo $itemId0</dt><dd class="list-item-content">Bar $itemId0</dd>
<dt class="list-item-label">Foo $itemId1</dt><dd class="list-item-content">Bar $itemId1</dd></dl>
EOD;

        $withAll = <<<EOD
<dl id="$identifier" class="definition-list"><dt class="list-item-label"><a href="/foo-$itemId0">Foo $itemId0</a></dt><dd class="list-item-content"><a href="/bar-$itemId0">Bar $itemId0</a></dd>
<dt class="list-item-label"><a href="/foo-$itemId1">Foo $itemId1</a></dt><dd class="list-item-content"><a href="/bar-$itemId1">Bar $itemId1</a></dd></dl>
EOD;

        // @codingStandardsIgnoreEnd

        return [
            'with nothing'     => [$identifier, false, false, false, $itemIds, $withNothing],
            'with links'       => [$identifier, true, false, false, $itemIds, $withLinks],
            'with label links' => [$identifier, true, true, false, $itemIds, $withLabelLinks],
            'with image'       => [$identifier, false, false, true, $itemIds, $withImage],
            'with all'         => [$identifier, true, true, true, $itemIds, $withAll],
        ];
    }

    /**
     * @dataProvider buildDefaultProvider
     *
     * @param string   $identifier
     * @param bool     $withLinks
     * @param bool     $withLabelLinks
     * @param bool     $withImages
     * @param string[] $itemIds
     * @param string   $expectedResult
     */
    public function testBuildDefault(
        string $identifier,
        bool $withLinks,
        bool $withLabelLinks,
        bool $withImages,
        array $itemIds,
        string $expectedResult
    ) {
        $entity = $this->createEntity($identifier, ...$itemIds)
            ->setWithLinks($withLinks)
            ->setWithLabelLinks($withLabelLinks)
            ->setWithImages($withImages);

        $actualResult = $this->sut->build($entity);

        $this->assertSame($expectedResult, $actualResult->getTemplates()['body']);
    }

    /**
     * @return array
     */
    public function buildVeryCustomProvider(): array
    {
        $identifier = 'isdfk';
        $itemId0    = 'veowq';
        $itemId1    = 'zzoel';
        $itemIds    = [$itemId0, $itemId1];

        // @codingStandardsIgnoreStart
        $withNothing = <<<EOD
<div id="$identifier" class="definition-list d"><ul class="it"><li class="l">Foo ${itemId0}</li><li class="c">Bar ${itemId0}</li></ul>
<ul class="it"><li class="l">Foo ${itemId1}</li><li class="c">Bar ${itemId1}</li></ul></div>
EOD;

        $withLinks = <<<EOD
<div id="$identifier" class="definition-list d"><ul class="it"><li class="l"><a href="/bar-$itemId0">Foo ${itemId0}</a></li><li class="c"><a href="/bar-$itemId0">Bar ${itemId0}</a></li></ul>
<ul class="it"><li class="l"><a href="/bar-$itemId1">Foo ${itemId1}</a></li><li class="c"><a href="/bar-$itemId1">Bar ${itemId1}</a></li></ul></div>
EOD;

        $withLabelLinks = <<<EOD
<div id="$identifier" class="definition-list d"><ul class="it"><li class="l"><a href="/foo-$itemId0">Foo ${itemId0}</a></li><li class="c"><a href="/bar-$itemId0">Bar ${itemId0}</a></li></ul>
<ul class="it"><li class="l"><a href="/foo-$itemId1">Foo ${itemId1}</a></li><li class="c"><a href="/bar-$itemId1">Bar ${itemId1}</a></li></ul></div>
EOD;

        $withImage = <<<EOD
<div id="$identifier" class="definition-list d"><ul class="it"><li class="l">Foo ${itemId0}</li><li class="c">Bar ${itemId0}</li></ul>
<ul class="it"><li class="l">Foo ${itemId1}</li><li class="c">Bar ${itemId1}</li></ul></div>
EOD;

        $withAll = <<<EOD
<div id="$identifier" class="definition-list d"><ul class="it"><li class="l"><a href="/foo-$itemId0">Foo ${itemId0}</a></li><li class="c"><a href="/bar-$itemId0">Bar ${itemId0}</a></li></ul>
<ul class="it"><li class="l"><a href="/foo-$itemId1">Foo ${itemId1}</a></li><li class="c"><a href="/bar-$itemId1">Bar ${itemId1}</a></li></ul></div>
EOD;

        // @codingStandardsIgnoreEnd

        return [
            'with nothing'     => [$identifier, false, false, false, $itemIds, $withNothing],
            'with links'       => [$identifier, true, false, false, $itemIds, $withLinks],
            'with label links' => [$identifier, true, true, false, $itemIds, $withLabelLinks],
            'with image'       => [$identifier, false, false, true, $itemIds, $withImage],
            'with all'         => [$identifier, true, true, true, $itemIds, $withAll],
        ];
    }

    /**
     * @dataProvider buildVeryCustomProvider
     *
     * @param string   $identifier
     * @param bool     $withLinks
     * @param bool     $withLabelLinks
     * @param bool     $withImages
     * @param string[] $itemIds
     * @param string   $expectedResult
     */
    public function testBuildVeryCustom(
        string $identifier,
        bool $withLinks,
        bool $withLabelLinks,
        bool $withImages,
        array $itemIds,
        string $expectedResult
    ) {
        $entity = $this->createEntity($identifier, ...$itemIds)
            ->setWithLinks($withLinks)
            ->setWithLabelLinks($withLabelLinks)
            ->setWithImages($withImages);

        $attributes     = [
            IContentList::WITH_LABEL_OPTION  => '1',
            IContentList::WITH_IMAGES_OPTION => '1',
            IContentList::LIST_TAG           => Html5::TAG_DIV,
            IContentList::LIST_CLASS         => 'd',
            IContentList::ITEM_TAG           => Html5::TAG_UL,
            IContentList::ITEM_CLASS         => 'it',
            IContentList::LABEL_TAG          => Html5::TAG_LI,
            IContentList::LABEL_CLASS        => 'l',
            IContentList::CONTENT_TAG        => Html5::TAG_LI,
            IContentList::CONTENT_CLASS      => 'c',
            IContentList::IMAGE_TAG          => Html5::TAG_LI,
            IContentList::IMAGE_CLASS        => 'im',
        ];
        $parsedTemplate = new ParsedTemplate('', '', $attributes);

        $actualResult = $this->sut->build($entity, $parsedTemplate);

        $this->assertSame($expectedResult, $actualResult->getTemplates()['body']);
    }
}
