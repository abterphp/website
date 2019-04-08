<?php

declare(strict_types=1);

namespace AbterPhp\Website\Events\Listeners;

use AbterPhp\Framework\Events\TemplateEngineReady;
use AbterPhp\Framework\Template\ILoader;
use AbterPhp\Website\Template\BlockLoader as Loader;

class TemplateInitializer
{
    const TEMPLATE_TYPE = 'block';

    /** @var ILoader */
    protected $loader;

    /**
     * TemplateRegistrar constructor.
     *
     * @param Loader $loader
     */
    public function __construct(Loader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param TemplateEngineReady $event
     */
    public function handle(TemplateEngineReady $event)
    {
        $event->getEngine()->getRenderer()->addLoader(static::TEMPLATE_TYPE, $this->loader);
    }
}
