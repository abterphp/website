<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory;

use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Website\Domain\Entities\ContentList as Entity;
use AbterPhp\Website\Domain\Entities\ContentListItem as Item;
use AbterPhp\Website\Domain\Entities\ContentListType as Type;
use AbterPhp\Website\Form\Factory\ContentList\Advanced as AdvancedFactory;
use AbterPhp\Website\Form\Factory\ContentList\Item as ItemFactory;
use AbterPhp\Website\Orm\ContentListItemRepo as ItemRepo;
use AbterPhp\Website\Orm\ContentListTypeRepo as TypeRepo;
use Casbin\Enforcer;
use Opulence\Http\Requests\RequestMethods;
use Opulence\Sessions\ISession;
use Opulence\Sessions\Session;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ContentListTest extends TestCase
{
    /** @var ContentList - System Under Test */
    protected $sut;

    /** @var ISession|MockObject */
    protected $sessionMock;

    /** @var ITranslator|MockObject */
    protected $translatorMock;

    /** @var TypeRepo|MockObject */
    protected $typeRepoMock;

    /** @var ItemRepo|MockObject */
    protected $itemRepoMock;

    /** @var AdvancedFactory */
    protected $advancedFactoryMock;

    /** @var ItemFactory */
    protected $itemFactoryMock;

    /** @var Enforcer|MockObject */
    protected $enforcerMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->sessionMock = $this->createMock(Session::class);
        $this->sessionMock->expects($this->any())->method('get')->willReturnArgument(0);

        $this->translatorMock = $this->createMock(ITranslator::class);
        $this->translatorMock->expects($this->any())->method('translate')->willReturnArgument(0);

        $this->typeRepoMock = $this->createMock(TypeRepo::class);
        $this->itemRepoMock = $this->createMock(ItemRepo::class);

        $this->advancedFactoryMock = new AdvancedFactory($this->translatorMock);
        $this->itemFactoryMock     = new ItemFactory();

        $this->enforcerMock = $this->createMock(Enforcer::class);

        $this->sut = new ContentList(
            $this->sessionMock,
            $this->translatorMock,
            $this->typeRepoMock,
            $this->itemRepoMock,
            $this->advancedFactoryMock,
            $this->itemFactoryMock,
            $this->enforcerMock
        );
    }

    /**
     * @return array
     */
    public function createProvider(): array
    {
        return [
            'advanced allowed'     => [
                true,
                [],
                [
                    'POST',
                    'CSRF',
                    'type_id',
                    '="name"',
                    'identifier',
                    'classes',
                    'with_image',
                    'with_links',
                    'with_html',
                ],
                [],
            ],
            'advanced not allowed' => [
                false,
                [],
                [
                    'POST',
                    'CSRF',
                    'type_id',
                    '="name"',
                    'identifier',
                    'classes',
                    'with_image',
                    'with_links',
                    'with_html',
                ],
                [],
            ],
        ];
    }

    /**
     * @dataProvider createProvider
     *
     * @param bool     $advancedAllowed
     * @param Item[]   $items
     * @param string[] $contains
     * @param string[] $missing
     */
    public function testCreate(bool $advancedAllowed, array $items, array $contains, array $missing)
    {
        $action   = 'foo';
        $method   = RequestMethods::POST;
        $showUrl  = 'bar';
        $entityId = '4571f468-8d7a-4680-81b5-fb747abaf580';
        $name     = 'Blah';
        $type0    = new Type('5f480eb5-1a54-4f5c-8303-59ae466ada68', 'LT 126', 'lt-126');
        $type1    = new Type('11325e40-1b6b-4820-8d4b-548a572acd02', 'LT 129', 'lt-129');

        $this->enforcerMock->expects($this->any())->method('enforce')->willReturn($advancedAllowed);
        $this->typeRepoMock->expects($this->any())->method('getAll')->willReturn([$type0, $type1]);
        $this->itemRepoMock->expects($this->any())->method('getByListId')->willReturn([]);

        $entity = new Entity('', '', '', '', false, false, false, false, false);

        $form = (string)$this->sut->create($action, $method, $showUrl, $entity);

        $this->assertStringContainsString($action, $form);
        $this->assertStringContainsString($showUrl, $form);
        foreach ($contains as $needle) {
            $this->assertStringContainsString($needle, $form);
        }
        foreach ($missing as $needle) {
            $this->assertStringNotContainsString($needle, $form);
        }
    }

    /**
     * @return array
     */
    public function updateProvider(): array
    {
        $itemId0 = 'cc1288f3-4873-4438-be60-7d7c1a26ddc6';
        $itemId1 = '3ce10679-3e96-4f11-9058-54bb429fbdbd';
        $listId0 = '4571f468-8d7a-4680-81b5-fb747abaf580';

        $containsAll = [
            'POST',
            'CSRF',
            'type_id',
            '="name"',
            'identifier',
            'classes',
            'with_image',
            'with_links',
            'with_body',
            'with_html',
        ];

        return [
            'unprotected'     => [
                false,
                false,
                [],
                $containsAll,
                [],
            ],
            'protected'       => [
                false,
                true,
                [],
                ['POST', 'CSRF'],
                ['type_id', '="name"', 'identifier', 'classes', 'with_image', 'with_links', 'with_body', 'with_html'],
            ],
            'admin protected' => [
                true,
                true,
                [],
                $containsAll,
                [],
            ],
            'with items'      => [
                true,
                true,
                [
                    new Item($itemId0, $listId0, '', '', '', '', '', '', ''),
                    new Item($itemId1, $listId0, '', '', '', '', '', '', ''),
                ],
                array_merge($containsAll, [$itemId0, $itemId1]),
                [],
            ],
        ];
    }

    /**
     * @dataProvider updateProvider
     *
     * @param bool     $advancedAllowed
     * @param bool     $protected
     * @param Item[]   $items
     * @param string[] $contains
     * @param string[] $missing
     */
    public function testUpdate(bool $advancedAllowed, bool $protected, array $items, array $contains, array $missing)
    {
        $action     = 'foo';
        $method     = RequestMethods::POST;
        $showUrl    = 'bar';
        $entityId   = '4571f468-8d7a-4680-81b5-fb747abaf580';
        $name       = 'Blah';
        $identifier = 'blah';
        $classes    = 'blah1 blah2';
        $withLinks  = true;
        $withImage  = true;
        $withBody   = true;
        $withHtml   = true;
        $type0      = new Type('5f480eb5-1a54-4f5c-8303-59ae466ada68', 'LT 126', 'lt-126');
        $type1      = new Type('11325e40-1b6b-4820-8d4b-548a572acd02', 'LT 129', 'lt-129');

        $this->enforcerMock->expects($this->any())->method('enforce')->willReturn($advancedAllowed);
        $this->typeRepoMock->expects($this->any())->method('getAll')->willReturn([$type0, $type1]);
        $this->itemRepoMock->expects($this->any())->method('getByListId')->willReturn($items);

        $entity = new Entity(
            $entityId,
            $name,
            $identifier,
            $classes,
            $protected,
            $withLinks,
            $withImage,
            $withBody,
            $withHtml,
            $type0
        );

        $form = (string)$this->sut->create($action, $method, $showUrl, $entity);

        $this->assertStringContainsString($action, $form);
        $this->assertStringContainsString($showUrl, $form);
        foreach ($contains as $needle) {
            $this->assertStringContainsString($needle, $form);
        }
        foreach ($missing as $needle) {
            $this->assertStringNotContainsString($needle, $form);
        }
    }
}
