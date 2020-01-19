<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Builder\ContentList;

use AbterPhp\Framework\Template\IBuilder;

class Simple extends Base implements IBuilder
{
    use ItemTrait;

    const IDENTIFIER = 'simple';

    protected $defaultListClass = 'simple';

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return static::IDENTIFIER;
    }
}
