<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory\Table\Header;

use AbterPhp\Framework\Grid\Factory\Table\HeaderFactory;

class Page extends HeaderFactory
{
    const GROUP_ID         = 'page-id';
    const GROUP_IDENTIFIER = 'page-identifier';
    const GROUP_TITLE      = 'page-title';

    const HEADER_ID         = 'website:pageId';
    const HEADER_IDENTIFIER = 'website:pageIdentifier';
    const HEADER_TITLE      = 'website:pageTitle';

    /** @var array */
    protected $headers = [
        self::GROUP_ID         => self::HEADER_ID,
        self::GROUP_IDENTIFIER => self::HEADER_IDENTIFIER,
        self::GROUP_TITLE      => self::HEADER_TITLE,
    ];

    /** @var array */
    protected $inputNames = [
        self::GROUP_ID         => 'id',
        self::GROUP_IDENTIFIER => 'identifier',
        self::GROUP_TITLE      => 'title',
    ];

    /** @var array */
    protected $fieldNames = [
        self::GROUP_ID         => 'pages.id',
        self::GROUP_IDENTIFIER => 'pages.identifier',
        self::GROUP_TITLE      => 'pages.title',
    ];
}
