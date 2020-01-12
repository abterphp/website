<?php

declare(strict_types=1);

namespace AbterPhp\Website\Validation\Factory;

use AbterPhp\Admin\TestDouble\Validation\StubRulesFactory;
use AbterPhp\Framework\Validation\Rules\ExactlyOne;
use AbterPhp\Framework\Validation\Rules\Forbidden;
use AbterPhp\Framework\Validation\Rules\Uuid;
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
                'forbidden'  => new Forbidden(),
                'uuid'       => new Uuid(),
                'exactlyOne' => new ExactlyOne(),
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
                    'identifier'  => 'foo',
                    'title'       => 'Foo',
                    'lead'        => 'bar',
                    'body'        => 'baz',
                    'is_draft'    => '1',
                    'category_id' => '5c032f90-bf10-4a77-81aa-b0b1254a8f66',
                    'layout_id'   => 'ebc97435-7280-4a67-855c-5d1ef0a2fd40',
                    'layout'      => '',
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
            'invalid-id-present'                              => [
                [
                    'id'        => 'baf16ace-8fae-48a8-bbad-a610d7960e31',
                    'title'     => 'Foo',
                    'layout_id' => 'ebc97435-7280-4a67-855c-5d1ef0a2fd40',
                ],
                false,
            ],
            'invalid-category-id-not-uuid'                    => [
                [
                    'layout_id' => 'ebc97435-7280-4a67-855c-5d1ef0a2fd4',
                ],
                false,
            ],
            'invalid-is-draft-is-not-numeric'                 => [
                [
                    'is_draft'  => 'foo',
                    'layout_id' => 'ebc97435-7280-4a67-855c-5d1ef0a2fd40',
                ],
                false,
            ],
            'invalid-layout-id-not-uuid'                      => [
                [
                    'category_id' => '5c032f90-bf10-4a77-81aa-b0b1254a8f6',
                    'layout_id'   => 'ebc97435-7280-4a67-855c-5d1ef0a2fd40',
                ],
                false,
            ],
            'invalid-layout-and-layout-id-missing'            => [
                [
                ],
                false,
            ],
            'invalid-layout-missing-and-layout-id-empty'      => [
                [
                    'layout_id' => '',
                ],
                false,
            ],
            'invalid-layout-empty-and-layout-id-missing'      => [
                [
                    'layout' => '',
                ],
                false,
            ],
            'invalid-layout-and-layout-id-empty'              => [
                [
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

        $actualResult = $validator->isValid($data);

        $this->assertSame($expectedResult, $actualResult);
    }
}
