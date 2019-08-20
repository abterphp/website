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
            [
                'uuid'       => new Uuid(),
                'atLeastOne' => new AtLeastOne(),
            ]
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
            'valid-data'                              => [
                [
                    'id'         => '465c91df-9cc7-47e2-a2ef-8fe645753148',
                    'identifier' => 'foo',
                    'title'      => 'bar',
                    'body'       => 'baz',
                    'layout'     => 'quix',
                    'layout_id'  => '2d60b546-ddbc-4fc4-be66-a2824e61334f',
                ],
                true,
            ],
            'valid-data-with-layout-id'               => [
                [
                    'id'         => '465c91df-9cc7-47e2-a2ef-8fe645753148',
                    'identifier' => 'foo',
                    'title'      => 'bar',
                    'body'       => 'baz',
                    'layout_id'  => '2d60b546-ddbc-4fc4-be66-a2824e61334f',
                ],
                true,
            ],
            'valid-data-missing-all-not-required'     => [
                [
                    'id'     => '465c91df-9cc7-47e2-a2ef-8fe645753148',
                    'title'  => 'bar',
                    'layout' => 'quix',
                ],
                true,
            ],
            'invalid-id-not-uuid'                     => [
                [
                    'id'     => '465c91df-9cc7-47e2-a2ef-8fe64575314',
                    'title'  => 'bar',
                    'layout' => 'foo',
                ],
                false,
            ],
            'invalid-id-layout-and-layout-id-missing' => [
                [
                    'id'    => '465c91df-9cc7-47e2-a2ef-8fe64575314',
                    'title' => 'bar',
                ],
                false,
            ],
            'invalid-id-layout-and-layout-id-empty'   => [
                [
                    'id'        => '465c91df-9cc7-47e2-a2ef-8fe64575314',
                    'title'     => 'bar',
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
