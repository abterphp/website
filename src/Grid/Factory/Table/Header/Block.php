<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory\Table\Header;

use AbterPhp\Framework\Grid\Factory\Table\HeaderFactory;

class Block extends HeaderFactory
{
    const GROUP_IDENTIFIER = 'block-identifier';
    const GROUP_TITLE      = 'block-title';

    const HEADER_IDENTIFIER = 'website:blockIdentifier';
    const HEADER_TITLE      = 'website:blockTitle';

    /** @var array */
    protected $headers = [
        self::GROUP_IDENTIFIER => self::HEADER_IDENTIFIER,
        self::GROUP_TITLE      => self::HEADER_TITLE,
    ];

    /** @var array */
    protected $inputNames = [
        self::GROUP_IDENTIFIER => 'identifier',
        self::GROUP_TITLE      => 'title',
    ];

    /** @var array */
    protected $fieldNames = [
        self::GROUP_IDENTIFIER => 'blocks.identifier',
        self::GROUP_TITLE      => 'blocks.title',
    ];
}
