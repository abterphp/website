<?php

declare(strict_types=1);

namespace AbterPhp\Website\Events\Listeners;

use AbterPhp\Framework\Events\AuthReady;
use AbterPhp\Website\Authorization\PageCategoryProvider as AuthProvider;

class AuthInitializer
{
    /** @var AuthProvider */
    protected $authProvider;

    /**
     * AuthInitializer constructor.
     *
     * @param AuthProvider $authProvider
     */
    public function __construct(AuthProvider $authProvider)
    {
        $this->authProvider = $authProvider;
    }

    /**
     * @param AuthReady $event
     */
    public function handle(AuthReady $event)
    {
        $event->getAdapter()->registerAdapter($this->authProvider);
    }
}
