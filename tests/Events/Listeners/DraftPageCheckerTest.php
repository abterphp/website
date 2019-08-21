<?php

declare(strict_types=1);

namespace AbterPhp\Website\Events\Listeners;

use AbterPhp\Framework\I18n\ITranslator;
use Casbin\Enforcer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DraftPageCheckerTest extends TestCase
{
    /** @var DraftPageChecker - System Under Test */
    protected $sut;

    /** @var Enforcer|MockObject */
    protected $enforcerMock;

    /** @var ITranslator|MockObject */
    protected $translatorMock;

    public function setUp(): void
    {
        $this->enforcerMock   = $this->createMock(Enforcer::class);
        $this->translatorMock = $this->createMock(ITranslator::class);

        $this->sut = new DraftPageChecker($this->enforcerMock, $this->translatorMock);
    }

    public function testHandle()
    {
        $this->markTestIncomplete();
    }
}
