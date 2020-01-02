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

    public function testBuildOneList()
    {
        $expectedResult = '<ul id="isdfk" class="unordered siakwl "><li>Foo veowq</li>' . PHP_EOL;
        $expectedResult .= '<li>Foo zzoel</li>' . PHP_EOL;
        $expectedResult .= '</ul>' . PHP_EOL;

        $typeName   = 'siakwl';
        $identifier = 'isdfk';

        $entity = $this->createEntity($typeName, $identifier, 'veowq', 'zzoel');

        $actualResult = $this->sut->build($entity);

        $this->assertSame($expectedResult, $actualResult->getTemplates()['body']);
    }
}
