<?php

namespace AbterPhp\Website\Bootstrappers\Http\Views;

use AbterPhp\Website\Http\Views\Builders\WebsiteBuilder;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\IContainer;
use Opulence\Views\Factories\IViewFactory;
use Opulence\Views\IView;

/**
 * Defines the view builders bootstrapper
 */
class BuildersBootstrapper extends Bootstrapper
{
    /**
     * @inheritdoc
     */
    public function registerBindings(IContainer $container)
    {
        /** @var IViewFactory $viewFactory */
        $viewFactory = $container->resolve(IViewFactory::class);

        $viewFactory->registerBuilder(
            'layouts/frontend/default',
            function (IView $view) use ($container) {
                /** @var IEventDispatcher $eventDispatcher */
                $eventDispatcher = $container->resolve(IEventDispatcher::class);

                /** @see WebsiteBuilder::build() */
                return (new WebsiteBuilder($eventDispatcher))->build($view);
            }
        );
        $viewFactory->registerBuilder(
            'layouts/frontend/empty',
            function (IView $view) use ($container) {
                /** @var IEventDispatcher $eventDispatcher */
                $eventDispatcher = $container->resolve(IEventDispatcher::class);

                /** @see WebsiteBuilder::build() */
                return (new WebsiteBuilder($eventDispatcher))->build($view);
            }
        );
    }
}
