<?php

declare(strict_types=1);

namespace AbterPhp\Website\Validation\Factory;

use AbterPhp\Admin\TestDouble\Validation\StubRulesFactory;
use AbterPhp\Framework\Validation\Rules\Uuid;
use Opulence\Validation\IValidator;
use Opulence\Validation\Rules\Factories\RulesFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BlockLayoutTest extends TestCase
{
    /** @var BlockLayout - System Under Test */
    protected $sut;

    /** @var RulesFactory|MockObject */
    protected $rulesFactoryMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->rulesFactoryMock = StubRulesFactory::createRulesFactory($this, ['uuid' => new Uuid()]);

        $this->sut = new BlockLayout($this->rulesFactoryMock);
    }

    /**
     * @return array
     */
    public function createValidatorProvider(): array
    {
        return [
            'empty-data'                          => [
                [],
                false,
            ],
            'valid-data'                          => [
                [
                    'id'         => '465c91df-9cc7-47e2-a2ef-8fe645753148',
                    'identifier' => 'foo',
                    'body'       => 'bar',
                ],
                true,
            ],
            'valid-data-missing-all-not-required' => [
                [
                    'identifier' => 'foo',
                ],
                true,
            ],
            'invalid-id-not-uuid'                 => [
                [
                    'id'         => '465c91df-9cc7-47e2-a2ef-8fe64575314',
                    'identifier' => 'foo',
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
