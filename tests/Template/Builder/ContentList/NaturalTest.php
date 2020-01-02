<?php

namespace AbterPhp\Website\Template\Builder\ContentList;

class NaturalTest extends ContentListTest
{
    /** @var Natural - System Under Test */
    protected $sut;

    public function setUp(): void
    {
        $this->sut = new Natural();
    }

    public function testBuildOneList()
    {
        $expectedResult = '<div id="isdfk" class="natural siakwl "><div>Foo veowq</div>' . PHP_EOL;
        $expectedResult .= '<div>Foo zzoel</div>' . PHP_EOL;
        $expectedResult .= '</div>' . PHP_EOL;

        $typeName   = 'siakwl';
        $identifier = 'isdfk';

        $entity = $this->createEntity($typeName, $identifier, 'veowq', 'zzoel');

        $actualResult = $this->sut->build($entity);

        $this->assertSame($expectedResult, $actualResult->getTemplates()['body']);
    }
}
