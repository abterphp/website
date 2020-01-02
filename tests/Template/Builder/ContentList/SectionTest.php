<?php

namespace AbterPhp\Website\Template\Builder\ContentList;

class SectionTest extends ContentListTest
{
    /** @var Section - System Under Test */
    protected $sut;

    public function setUp(): void
    {
        $this->sut = new Section();
    }

    public function testBuildOneList()
    {
        $expectedResult = '<section id="isdfk" class="section siakwl "><div>Foo veowq</div>' . PHP_EOL;
        $expectedResult .= '<div>Foo zzoel</div>' . PHP_EOL;
        $expectedResult .= '</section>' . PHP_EOL;

        $typeName   = 'siakwl';
        $identifier = 'isdfk';

        $entity = $this->createEntity($typeName, $identifier, 'veowq', 'zzoel');

        $actualResult = $this->sut->build($entity);

        $this->assertSame($expectedResult, $actualResult->getTemplates()['body']);
    }
}
