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

class BlockTest extends TestCase
{
    /** @var Block - System Under Test */
    protected $sut;

    /** @var RulesFactory|MockObject */
    protected $rulesFactoryMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->rulesFactoryMock = StubRulesFactory::createRulesFactory(
            $this,
            ['forbidden' => new Forbidden(), 'exactlyOne' => new ExactlyOne(), 'uuid' => new Uuid()]
        );

        $this->sut = new Block($this->rulesFactoryMock);
    }

    /**
     * @return array
     */
    public function createValidatorProvider(): array
    {
        return [
            'empty-data'                              => [
                [],
                false,
            ],
            'valid-data-with-layout'                  => [
                [
                    'identifier' => 'foo',
                    'title'      => 'bar',
                    'body'       => 'baz',
                    'layout'     => 'quix',
                    'layout_id'  => '',
                ],
                true,
            ],
            'valid-data-with-layout-id'               => [
                [
                    'identifier' => 'foo',
                    'title'      => 'bar',
                    'body'       => 'baz',
                    'layout_id'  => '2d60b546-ddbc-4fc4-be66-a2824e61334f',
                ],
                true,
            ],
            'valid-data-missing-all-not-required'     => [
                [
                    'title'  => 'bar',
                    'layout' => 'quix',
                ],
                true,
            ],
            'invalid-id-present'                      => [
                [
                    'id'    => 'baf16ace-8fae-48a8-bbad-a610d7960e31',
                    'title' => 'bar',
                ],
                false,
            ],
//            'invalid-layout-and-layout-id-missing' => [
//                [
//                    'title' => 'bar',
//                ],
//                false,
//            ],
//            'invalid-layout-and-layout-id-empty'   => [
//                [
//                    'title'     => 'bar',
//                    'layout'    => '',
//                    'layout_id' => '',
//                ],
//                false,
//            ],
            'invalid-both-layout-and-layout-id-empty'   => [
                [
                    'title'     => 'bar',
                    'layout'    => 'baz',
                    'layout_id' => '2d60b546-ddbc-4fc4-be66-a2824e61334f',
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
