<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory;

use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Website\Domain\Entities\ContentList as Entity;
use AbterPhp\Website\Domain\Entities\ContentListItem as Item;
use AbterPhp\Website\Form\Factory\ContentList\Item as ItemFactory;
use AbterPhp\Website\Orm\ContentListItemRepo as ItemRepo;
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

    /** @var ItemRepo|MockObject */
    protected $itemRepoMock;

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

        $this->itemRepoMock = $this->createMock(ItemRepo::class);

        $this->itemFactoryMock = new ItemFactory();

        $this->enforcerMock = $this->createMock(Enforcer::class);

        $this->sut = new ContentList(
            $this->sessionMock,
            $this->translatorMock,
            $this->itemRepoMock,
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
                    '="name"',
                    'identifier',
                    'classes',
                    'with_links',
                    'with_label_links',
                    'with_html',
                    'with_image',
                    'with_classes',
                ],
                [],
            ],
            'advanced not allowed' => [
                false,
                [],
                [
                    'POST',
                    'CSRF',
                    '="name"',
                    'identifier',
                    'classes',
                    'with_links',
                    'with_label_links',
                    'with_html',
                    'with_image',
                    'with_classes',
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

        $this->enforcerMock->expects($this->any())->method('enforce')->willReturn($advancedAllowed);
        $this->itemRepoMock->expects($this->any())->method('getByListId')->willReturn([]);

        $entity = new Entity('', '', '', '', false, false, false, false, false, false);

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
            '="name"',
            'identifier',
            'classes',
            'with_links',
            'with_label_links',
            'with_html',
            'with_image',
            'with_classes',
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
                ['identifier', 'classes', 'with_links', 'with_label_links', 'with_html', 'with_image', 'with_classes'],
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
                    new Item($itemId0, $listId0, '', '', '', '', '', '', '', ''),
                    new Item($itemId1, $listId0, '', '', '', '', '', '', '', ''),
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
        $action        = 'foo';
        $method        = RequestMethods::POST;
        $showUrl       = 'bar';
        $entityId      = '4571f468-8d7a-4680-81b5-fb747abaf580';
        $name          = 'Blah';
        $identifier    = 'blah';
        $classes       = 'blah1 blah2';
        $withLinks     = true;
        $withNameLinks = true;
        $withHtml      = true;
        $withImages    = true;
        $withClasses   = true;

        $this->enforcerMock->expects($this->any())->method('enforce')->willReturn($advancedAllowed);
        $this->itemRepoMock->expects($this->any())->method('getByListId')->willReturn($items);

        $entity = new Entity(
            $entityId,
            $name,
            $identifier,
            $classes,
            $protected,
            $withLinks,
            $withNameLinks,
            $withHtml,
            $withImages,
            $withClasses
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
