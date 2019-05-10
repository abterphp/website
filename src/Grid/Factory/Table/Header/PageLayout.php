<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory\Table\Header;

use AbterPhp\Framework\Grid\Factory\Table\HeaderFactory;

class PageLayout extends HeaderFactory
{
    const GROUP_IDENTIFIER = 'pageLayout-identifier';
    const GROUP_TITLE      = 'pageLayout-title';

    const HEADER_IDENTIFIER = 'website:pageLayoutIdentifier';

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
        self::GROUP_IDENTIFIER => 'page_layouts.identifier',
        self::GROUP_TITLE      => 'page_layouts.title',
    ];
}
