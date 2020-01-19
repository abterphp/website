<?php

declare(strict_types=1);

namespace AbterPhp\Website\Validation\Factory;

use AbterPhp\Admin\TestDouble\Validation\StubRulesFactory;
use AbterPhp\Framework\Validation\Rules\Forbidden;
use Opulence\Validation\Rules\Factories\RulesFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ContentListTest extends TestCase
{
    /** @var ContentList - System Under Test */
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

        $this->sut = new ContentList($this->rulesFactoryMock);
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
                    'identifier'       => 'foo',
                    'name'             => 'bar',
                    'classes'          => 'baz',
                    'protected'        => '1',
                    'with_links'       => '1',
                    'with_label_links' => '1',
                    'with_html'        => '1',
                    'with_images'      => '1',
                    'with_classes'     => '1',
                ],
                true,
            ],
            'valid-data-missing-all-not-required' => [
                [
                    'name' => 'bar',
                ],
                true,
            ],
            'invalid-id-present'                  => [
                [
                    'id'   => 'baf16ace-8fae-48a8-bbad-a610d7960e31',
                    'name' => 'bar',
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
