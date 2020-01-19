<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Builder\ContentList;

use AbterPhp\Framework\Template\IBuilder;

class WithImage extends Base implements IBuilder
{
    use ItemTrait;

    const IDENTIFIER = 'with-image';

    protected $defaultListClass = 'with-image';

    protected $defaultWithImage = '1';

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return static::IDENTIFIER;
    }
}
