<?php

namespace AbterPhp\Website\Template\Builder\ContentList;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Template\ParsedTemplate;

class SimpleTest extends ContentListTest
{
    /** @var Simple - System Under Test */
    protected $sut;

    public function setUp(): void
    {
        $this->sut = new Simple();
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
<ul id="$identifier" class="simple"><li class="list-item">Bar $itemId0</li>
<li class="list-item">Bar $itemId1</li></ul>
EOD;

        $withLinks = <<<EOD
<ul id="$identifier" class="simple"><li class="list-item"><a href="/bar-$itemId0">Bar $itemId0</a></li>
<li class="list-item"><a href="/bar-$itemId1">Bar $itemId1</a></li></ul>
EOD;

        $withLabelLinks = <<<EOD
<ul id="$identifier" class="simple"><li class="list-item"><a href="/bar-$itemId0">Bar $itemId0</a></li>
<li class="list-item"><a href="/bar-$itemId1">Bar $itemId1</a></li></ul>
EOD;

        $withImage = <<<EOD
<ul id="$identifier" class="simple"><li class="list-item">Bar $itemId0</li>
<li class="list-item">Bar $itemId1</li></ul>
EOD;

        $withAll = <<<EOD
<ul id="$identifier" class="simple"><li class="list-item"><a href="/bar-$itemId0">Bar $itemId0</a></li>
<li class="list-item"><a href="/bar-$itemId1">Bar $itemId1</a></li></ul>
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
    public function buildWrappedProvider(): array
    {
        $identifier = 'isdfk';
        $itemId0    = 'veowq';
        $itemId1    = 'zzoel';
        $itemIds    = [$itemId0, $itemId1];

        // @codingStandardsIgnoreStart
        $withNothing = <<<EOD
<ul id="$identifier" class="simple"><li class="list-item"><span class="list-item-content">Bar $itemId0</span></li>
<li class="list-item"><span class="list-item-content">Bar $itemId1</span></li></ul>
EOD;

        $withLinks = <<<EOD
<ul id="$identifier" class="simple"><li class="list-item"><span class="list-item-content"><a href="/bar-$itemId0">Bar $itemId0</a></span></li>
<li class="list-item"><span class="list-item-content"><a href="/bar-$itemId1">Bar $itemId1</a></span></li></ul>
EOD;

        $withLabelLinks = <<<EOD
<ul id="$identifier" class="simple"><li class="list-item"><span class="list-item-content"><a href="/bar-$itemId0">Bar $itemId0</a></span></li>
<li class="list-item"><span class="list-item-content"><a href="/bar-$itemId1">Bar $itemId1</a></span></li></ul>
EOD;

        $withImage = <<<EOD
<ul id="$identifier" class="simple"><li class="list-item"><span class="list-item-content">Bar $itemId0</span></li>
<li class="list-item"><span class="list-item-content">Bar $itemId1</span></li></ul>
EOD;

        $withAll = <<<EOD
<ul id="$identifier" class="simple"><li class="list-item"><span class="list-item-content"><a href="/bar-$itemId0">Bar $itemId0</a></span></li>
<li class="list-item"><span class="list-item-content"><a href="/bar-$itemId1">Bar $itemId1</a></span></li></ul>
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
     * @dataProvider buildWrappedProvider
     *
     * @param string   $identifier
     * @param bool     $withLinks
     * @param bool     $withLabelLinks
     * @param bool     $withImages
     * @param string[] $itemIds
     * @param string   $expectedResult
     */
    public function testBuildWrapped(
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

        $attributes     = [IContentList::CONTENT_TAG => Html5::TAG_SPAN];
        $parsedTemplate = new ParsedTemplate('', '', $attributes);

        $actualResult = $this->sut->build($entity, $parsedTemplate);

        $this->assertSame($expectedResult, $actualResult->getTemplates()['body']);
    }

    /**
     * @return array
     */
    public function buildWithAllWithoutTagsProvider(): array
    {
        $identifier = 'isdfk';
        $itemId0    = 'veowq';
        $itemId1    = 'zzoel';
        $itemIds    = [$itemId0, $itemId1];

        // @codingStandardsIgnoreStart
        $withNothing = <<<EOD
<ul id="$identifier" class="simple"><li class="list-item">Foo ${itemId0}Bar ${itemId0}</li>
<li class="list-item">Foo ${itemId1}Bar ${itemId1}</li></ul>
EOD;

        $withLinks = <<<EOD
<ul id="$identifier" class="simple"><li class="list-item"><a href="/bar-$itemId0">Foo $itemId0</a><a href="/bar-$itemId0">Bar $itemId0</a></li>
<li class="list-item"><a href="/bar-$itemId1">Foo $itemId1</a><a href="/bar-$itemId1">Bar $itemId1</a></li></ul>
EOD;

        $withLabelLinks = <<<EOD
<ul id="$identifier" class="simple"><li class="list-item"><a href="/foo-$itemId0">Foo $itemId0</a><a href="/bar-$itemId0">Bar $itemId0</a></li>
<li class="list-item"><a href="/foo-$itemId1">Foo $itemId1</a><a href="/bar-$itemId1">Bar $itemId1</a></li></ul>
EOD;

        $withImage = <<<EOD
<ul id="$identifier" class="simple"><li class="list-item">Foo ${itemId0}Bar ${itemId0}<img src="/baz-${itemId0}.png" alt="Baz ${itemId0}"></li>
<li class="list-item">Foo ${itemId1}Bar ${itemId1}<img src="/baz-${itemId1}.png" alt="Baz ${itemId1}"></li></ul>
EOD;

        $withAll = <<<EOD
<ul id="$identifier" class="simple"><li class="list-item"><a href="/foo-$itemId0">Foo $itemId0</a><a href="/bar-$itemId0">Bar $itemId0</a><a href="/baz-$itemId0"><img src="/baz-${itemId0}.png" alt="Baz ${itemId0}"></a></li>
<li class="list-item"><a href="/foo-$itemId1">Foo $itemId1</a><a href="/bar-$itemId1">Bar $itemId1</a><a href="/baz-$itemId1"><img src="/baz-${itemId1}.png" alt="Baz ${itemId1}"></a></li></ul>
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
     * @dataProvider buildWithAllWithoutTagsProvider
     *
     * @param string   $identifier
     * @param bool     $withLinks
     * @param bool     $withLabelLinks
     * @param bool     $withImages
     * @param string[] $itemIds
     * @param string   $expectedResult
     */
    public function testBuildWithAllWithoutTags(
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
        ];
        $parsedTemplate = new ParsedTemplate('', '', $attributes);

        $actualResult = $this->sut->build($entity, $parsedTemplate);

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
<div id="$identifier" class="simple d"><ul class="it"><li class="l">Foo ${itemId0}</li><li class="c">Bar ${itemId0}</li></ul>
<ul class="it"><li class="l">Foo ${itemId1}</li><li class="c">Bar ${itemId1}</li></ul></div>
EOD;

        $withLinks = <<<EOD
<div id="$identifier" class="simple d"><ul class="it"><li class="l"><a href="/bar-$itemId0">Foo ${itemId0}</a></li><li class="c"><a href="/bar-$itemId0">Bar ${itemId0}</a></li></ul>
<ul class="it"><li class="l"><a href="/bar-$itemId1">Foo ${itemId1}</a></li><li class="c"><a href="/bar-$itemId1">Bar ${itemId1}</a></li></ul></div>
EOD;

        $withLabelLinks = <<<EOD
<div id="$identifier" class="simple d"><ul class="it"><li class="l"><a href="/foo-$itemId0">Foo ${itemId0}</a></li><li class="c"><a href="/bar-$itemId0">Bar ${itemId0}</a></li></ul>
<ul class="it"><li class="l"><a href="/foo-$itemId1">Foo ${itemId1}</a></li><li class="c"><a href="/bar-$itemId1">Bar ${itemId1}</a></li></ul></div>
EOD;

        $withImage = <<<EOD
<div id="$identifier" class="simple d"><ul class="it"><li class="l">Foo ${itemId0}</li><li class="c">Bar ${itemId0}</li><li class="im"><img src="/baz-${itemId0}.png" alt="Baz ${itemId0}"></li></ul>
<ul class="it"><li class="l">Foo ${itemId1}</li><li class="c">Bar ${itemId1}</li><li class="im"><img src="/baz-${itemId1}.png" alt="Baz ${itemId1}"></li></ul></div>
EOD;

        $withAll = <<<EOD
<div id="$identifier" class="simple d"><ul class="it"><li class="l"><a href="/foo-$itemId0">Foo ${itemId0}</a></li><li class="c"><a href="/bar-$itemId0">Bar ${itemId0}</a></li><li class="im"><a href="/baz-$itemId0"><img src="/baz-${itemId0}.png" alt="Baz ${itemId0}"></a></li></ul>
<ul class="it"><li class="l"><a href="/foo-$itemId1">Foo ${itemId1}</a></li><li class="c"><a href="/bar-$itemId1">Bar ${itemId1}</a></li><li class="im"><a href="/baz-$itemId1"><img src="/baz-${itemId1}.png" alt="Baz ${itemId1}"></a></li></ul></div>
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
