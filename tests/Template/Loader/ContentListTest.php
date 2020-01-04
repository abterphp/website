<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Loader;

use AbterPhp\Framework\Exception\Config;
use AbterPhp\Framework\Template\IBuilder;
use AbterPhp\Framework\Template\IData;
use AbterPhp\Framework\Template\ParsedTemplate;
use AbterPhp\Website\Databases\Queries\ContentListCache as Cache;
use AbterPhp\Website\Domain\Entities\ContentList as Entity;
use AbterPhp\Website\Domain\Entities\ContentListItem as Item;
use AbterPhp\Website\Domain\Entities\ContentListType as Type;
use AbterPhp\Website\Orm\ContentListItemRepo as ItemRepo;
use AbterPhp\Website\Orm\ContentListRepo as Repo;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ContentListTest extends TestCase
{
    /** @var ContentList - System Under Test */
    protected $sut;

    /** @var Repo|MockObject */
    protected $repoMock;

    /** @var ItemRepo|MockObject */
    protected $itemRepoMock;

    /** @var Cache|MockObject */
    protected $cacheMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->repoMock     = $this->createMock(Repo::class);
        $this->itemRepoMock = $this->createMock(ItemRepo::class);
        $this->cacheMock    = $this->createMock(Cache::class);

        $this->sut = new ContentList($this->repoMock, $this->itemRepoMock, $this->cacheMock, []);
    }

    public function testLoadOne()
    {
        $typeName   = 'siakwl';
        $identifier = 'isdfk';

        $entity = $this->createEntity($typeName, $identifier, 'veowq', 'zzoel');

        $this->repoMock->expects($this->any())->method('getByIdentifiers')->willReturn([$entity]);
        $this->itemRepoMock->expects($this->any())->method('getByListIds')->willReturn($entity->getItems());

        $dataStub = $this->createMock(IData::class);
        $this->addBuilder($typeName, $dataStub);

        $parsedTemplates = [
            $identifier => [new ParsedTemplate('list', $identifier)],
        ];

        $actualResult = $this->sut->load($parsedTemplates);

        $this->assertSame([$dataStub], $actualResult);
    }

    public function testLoadThrowsExceptionWhenBuilderNotFound()
    {
        $this->expectException(Config::class);

        $typeName   = 'siakwl';
        $identifier = 'isdfk';

        $entity = $this->createEntity($typeName, $identifier, 'veowq', 'zzoel');

        $this->repoMock->expects($this->any())->method('getByIdentifiers')->willReturn([$entity]);
        $this->itemRepoMock->expects($this->any())->method('getByListIds')->willReturn($entity->getItems());

        $parsedTemplates = [
            $identifier => [new ParsedTemplate('list', $identifier)],
        ];

        $actualResult = $this->sut->load($parsedTemplates);

        $this->assertSame([$dataStub], $actualResult);
    }

    /**
     * @param string $typeName
     * @param IData  $data
     */
    protected function addBuilder(string $typeName, IData $data)
    {
        $builderMock = $this->createMock(IBuilder::class);
        $builderMock->expects($this->atLeastOnce())->method('build')->willReturn($data);

        $this->sut->addBuilder($typeName, $builderMock);
    }

    /**
     * @param string $typeName
     * @param string $identifier
     * @param string ...$itemPostixes
     *
     * @return Entity
     */
    protected function createEntity(string $typeName, string $identifier, string ...$itemPostixes): Entity
    {
        $entityId = "list-$identifier";

        $type   = new Type("type-$identifier", $typeName, '');
        $entity = new Entity($entityId, '', $identifier, '', false, false, false, false, false, $type);
        $items  = [];
        foreach ($itemPostixes as $postfix) {
            $entity->addItem($this->createItem($postfix, $entityId));
        }

        return $entity;
    }

    /**
     * @param string $postfix
     * @param string $listId
     *
     * @return Item
     */
    protected function createItem(string $postfix, string $listId): Item
    {
        $id       = "item-$postfix";
        $name     = "Foo $postfix";
        $nameHref = "/foo-$postfix";
        $body     = "Bar $postfix";
        $bodyHref = "/bar-$postfix";
        $imgSrc   = "/baz0-$postfix";
        $imgHref  = "/baz1-$postfix";
        $imgAlt   = "Baz $postfix";

        return new Item($id, $listId, $name, $nameHref, $body, $bodyHref, $imgSrc, $imgHref, $imgAlt);
    }

    public function testHasAnyChangedSinceCallsBlockCache()
    {
        $identifiers    = ['foo', 'bar', 'baz'];
        $cacheTime      = 'foo';
        $expectedResult = true;

        $this->cacheMock
            ->expects($this->once())
            ->method('hasAnyChangedSince')
            ->with($identifiers, $cacheTime)
            ->willReturn($expectedResult);

        $actualResult = $this->sut->hasAnyChangedSince($identifiers, $cacheTime);

        $this->assertSame($expectedResult, $actualResult);
    }
}
