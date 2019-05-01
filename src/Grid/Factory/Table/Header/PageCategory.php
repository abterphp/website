<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory\Table\Header;

use AbterPhp\Framework\Grid\Factory\Table\HeaderFactory;

class PageCategory extends HeaderFactory
{
    const GROUP_ID         = 'pageCategory-id';
    const GROUP_IDENTIFIER = 'pageCategory-identifier';
    const GROUP_NAME       = 'pageCategory-name';

    const HEADER_ID         = 'website:pageCategoryId';
    const HEADER_IDENTIFIER = 'website:pageCategoryIdentifier';

    /** @var array */
    protected $headers = [
        self::GROUP_ID         => self::HEADER_ID,
        self::GROUP_IDENTIFIER => self::HEADER_IDENTIFIER,
    ];

    /** @var array */
    protected $inputNames = [
        self::GROUP_ID         => 'id',
        self::GROUP_IDENTIFIER => 'identifier',
        self::GROUP_NAME       => 'name',
    ];

    /** @var array */
    protected $fieldNames = [
        self::GROUP_ID         => 'page_layouts.id',
        self::GROUP_IDENTIFIER => 'page_layouts.identifier',
        self::GROUP_NAME       => 'page_layouts.name',
    ];
}
