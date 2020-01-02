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

    public function testBuildOneList()
    {
        $expectedResult = '<ol id="isdfk" class="ordered siakwl "><li>Foo veowq</li>' . PHP_EOL;
        $expectedResult .= '<li>Foo zzoel</li>' . PHP_EOL;
        $expectedResult .= '</ol>' . PHP_EOL;

        $typeName   = 'siakwl';
        $identifier = 'isdfk';

        $entity = $this->createEntity($typeName, $identifier, 'veowq', 'zzoel');

        $actualResult = $this->sut->build($entity);

        $this->assertSame($expectedResult, $actualResult->getTemplates()['body']);
    }
}
