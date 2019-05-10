<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory\Table\Header;

use AbterPhp\Framework\Grid\Factory\Table\HeaderFactory;

class Page extends HeaderFactory
{
    const GROUP_IDENTIFIER = 'page-identifier';
    const GROUP_TITLE      = 'page-title';
    const GROUP_CATEGORY   = 'page-category';

    const HEADER_IDENTIFIER = 'website:pageIdentifier';
    const HEADER_TITLE      = 'website:pageTitle';
    const HEADER_CATEGORY   = 'website:pageCategory';

    /** @var array */
    protected $headers = [
        self::GROUP_IDENTIFIER => self::HEADER_IDENTIFIER,
        self::GROUP_TITLE      => self::HEADER_TITLE,
        self::GROUP_CATEGORY   => self::HEADER_CATEGORY,
    ];

    /** @var array */
    protected $inputNames = [
        self::GROUP_IDENTIFIER => 'identifier',
        self::GROUP_TITLE      => 'title',
    ];

    /** @var array */
    protected $fieldNames = [
        self::GROUP_IDENTIFIER => 'pages.identifier',
        self::GROUP_TITLE      => 'pages.title',
    ];
}
