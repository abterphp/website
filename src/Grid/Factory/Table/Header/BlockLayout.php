<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory\Table\Header;

use AbterPhp\Framework\Grid\Factory\Table\HeaderFactory;

class BlockLayout extends HeaderFactory
{
    const GROUP_IDENTIFIER = 'blockLayout-identifier';
    const GROUP_TITLE      = 'blockLayout-title';

    const HEADER_IDENTIFIER = 'website:blockLayoutIdentifier';

    /** @var array */
    protected $headers = [
        self::GROUP_IDENTIFIER => self::HEADER_IDENTIFIER,
    ];

    /** @var array */
    protected $inputNames = [
        self::GROUP_IDENTIFIER => 'identifier',
        self::GROUP_TITLE      => 'title',
    ];

    /** @var array */
    protected $fieldNames = [
        self::GROUP_IDENTIFIER => 'block_layouts.identifier',
        self::GROUP_TITLE      => 'block_layouts.title',
    ];
}
