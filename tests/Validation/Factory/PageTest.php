<?php

declare(strict_types=1);

namespace AbterPhp\Website\Validation\Factory;

use AbterPhp\Admin\TestDouble\Validation\StubRulesFactory;
use AbterPhp\Framework\Validation\Rules\AtLeastOne;
use AbterPhp\Framework\Validation\Rules\Uuid;
use Opulence\Validation\IValidator;
use Opulence\Validation\Rules\Factories\RulesFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PageTest extends TestCase
{
    /** @var Page - System Under Test */
    protected $sut;

    /** @var RulesFactory|MockObject */
    protected $rulesFactoryMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->rulesFactoryMock = StubRulesFactory::createRulesFactory(
            $this,
            [
                'uuid'       => new Uuid(),
                'atLeastOne' => new AtLeastOne(),
            ]
        );

        $this->sut = new Page($this->rulesFactoryMock);
    }

    /**
     * @return array
     */
    public function createValidatorProvider(): array
    {
        return [
            'empty-data'                                      => [
                [],
                false,
            ],
            'valid-data'                                      => [
                [
                    'id'          => '465c91df-9cc7-47e2-a2ef-8fe645753148',
                    'identifier'  => 'foo',
                    'title'       => 'Foo',
                    'lead'        => 'bar',
                    'body'        => 'baz',
                    'is_draft'    => '1',
                    'category_id' => '5c032f90-bf10-4a77-81aa-b0b1254a8f66',
                    'layout_id'   => 'ebc97435-7280-4a67-855c-5d1ef0a2fd40',
                    'layout'      => 'qux',
                    'header'      => 'thud',
                    'footer'      => 'grunt',
                    'css_files'   => 'bletch',
                    'js_files'    => 'fum',
                ],
                true,
            ],
            'valid-data-missing-all-not-required'             => [
                [
                    'title'     => 'Foo',
                    'layout_id' => 'ebc97435-7280-4a67-855c-5d1ef0a2fd40',
                ],
                true,
            ],
            'valid-data-missing-all-not-required-with-layout' => [
                [
                    'title'  => 'Foo',
                    'layout' => 'qux',
                ],
                true,
            ],
            'invalid-id-not-uuid'                             => [
                [
                    'id'        => '465c91df-9cc7-47e2-a2ef-8fe64575314',
                    'layout_id' => 'ebc97435-7280-4a67-855c-5d1ef0a2fd40',
                ],
                false,
            ],
            'invalid-category-id-not-uuid'                    => [
                [
                    'id'        => '465c91df-9cc7-47e2-a2ef-8fe645753148',
                    'layout_id' => 'ebc97435-7280-4a67-855c-5d1ef0a2fd4',
                ],
                false,
            ],
            'invalid-is-draft-is-not-numeric'                 => [
                [
                    'id'        => '465c91df-9cc7-47e2-a2ef-8fe645753148',
                    'is_draft'  => 'foo',
                    'layout_id' => 'ebc97435-7280-4a67-855c-5d1ef0a2fd40',
                ],
                false,
            ],
            'invalid-layout-id-not-uuid'                      => [
                [
                    'id'          => '465c91df-9cc7-47e2-a2ef-8fe645753148',
                    'category_id' => '5c032f90-bf10-4a77-81aa-b0b1254a8f6',
                    'layout_id'   => 'ebc97435-7280-4a67-855c-5d1ef0a2fd40',
                ],
                false,
            ],
            'invalid-layout-and-layout-id-missing'            => [
                [
                    'id' => '465c91df-9cc7-47e2-a2ef-8fe645753148',
                ],
                false,
            ],
            'invalid-layout-missing-and-layout-id-empty'      => [
                [
                    'id'        => '465c91df-9cc7-47e2-a2ef-8fe645753148',
                    'layout_id' => '',
                ],
                false,
            ],
            'invalid-layout-empty-and-layout-id-missing'      => [
                [
                    'id'     => '465c91df-9cc7-47e2-a2ef-8fe645753148',
                    'layout' => '',
                ],
                false,
            ],
            'invalid-layout-and-layout-id-empty'              => [
                [
                    'id'        => '465c91df-9cc7-47e2-a2ef-8fe645753148',
                    'layout'    => '',
                    'layout_id' => '',
                ],
                false,
            ],
        ];
    }

    /**
     * @dataProvider createValidatorProvider
     *
     * @param array $data
     * @param bool  $expectedResult
     */
    public function testCreateValidator(array $data, bool $expectedResult)
    {
        $validator = $this->sut->createValidator();

        $this->assertInstanceOf(IValidator::class, $validator);

        $actualResult = $validator->isValid($data);

        $this->assertSame($expectedResult, $actualResult);
    }
}
