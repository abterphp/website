<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory\Table\Header;

use AbterPhp\Admin\Grid\Factory\Table\HeaderFactory;

class BlockLayout extends HeaderFactory
{
    const GROUP_NAME       = 'blockLayout-name';
    const GROUP_IDENTIFIER = 'blockLayout-identifier';

    const HEADER_NAME       = 'website:blockLayoutName';
    const HEADER_IDENTIFIER = 'website:blockLayoutIdentifier';

    /** @var array */
    protected $headers = [
        self::GROUP_NAME       => self::HEADER_NAME,
        self::GROUP_IDENTIFIER => self::HEADER_IDENTIFIER,
    ];

    /** @var array */
    protected $inputNames = [
        self::GROUP_NAME       => 'name',
        self::GROUP_IDENTIFIER => 'identifier',
    ];

    /** @var array */
    protected $fieldNames = [
        self::GROUP_NAME       => 'block_layouts.name',
        self::GROUP_IDENTIFIER => 'block_layouts.identifier',
    ];
}
