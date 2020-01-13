<?php

declare(strict_types=1);

namespace AbterPhp\Website\Bootstrappers\Validation;

use AbterPhp\Admin\Bootstrappers\Validation\ValidatorBootstrapper as BaseBootstrapper;
use AbterPhp\Admin\Validation\Factory\User;
use AbterPhp\Admin\Validation\Factory\UserGroup;
use AbterPhp\Website\Validation\Factory\Block;
use AbterPhp\Website\Validation\Factory\BlockLayout;
use AbterPhp\Website\Validation\Factory\Page;
use AbterPhp\Website\Validation\Factory\PageCategory;
use AbterPhp\Website\Validation\Factory\PageLayout;

/**
 * Defines the validator bootstrapper
 */
class ValidatorBootstrapper extends BaseBootstrapper
{
    /**
     * @var array
     */
    protected $validatorFactories = [
        User::class,
        UserGroup::class,
        BlockLayout::class,
        Block::class,
        PageLayout::class,
        PageCategory::class,
        Page::class,
    ];
}
