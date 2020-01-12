<?php

declare(strict_types=1);

namespace AbterPhp\Website\Validation\Factory;

use AbterPhp\Admin\TestDouble\Validation\StubRulesFactory;
use AbterPhp\Framework\Validation\Rules\Forbidden;
use Opulence\Validation\Rules\Factories\RulesFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PageLayoutTest extends TestCase
{
    /** @var PageLayout - System Under Test */
    protected $sut;

    /** @var RulesFactory|MockObject */
    protected $rulesFactoryMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->rulesFactoryMock = StubRulesFactory::createRulesFactory(
            $this,
            ['forbidden' => new Forbidden()]
        );

        $this->sut = new PageLayout($this->rulesFactoryMock);
    }

    /**
     * @return array
     */
    public function createValidatorProvider(): array
    {
        return [
            'empty-data'         => [
                [],
                true,
            ],
            'valid-data'         => [
                [
                    'identifier' => 'foo',
                    'body'       => 'bar',
                    'header'     => 'baz',
                    'footer'     => 'quix',
                    'css_files'  => 'oof',
                    'js_files'   => 'rab',
                ],
                true,
            ],
            'invalid-id-present' => [
                [
                    'id' => 'baf16ace-8fae-48a8-bbad-a610d7960e31',
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
