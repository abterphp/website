<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory\Table\Header;

use AbterPhp\Admin\Grid\Factory\Table\HeaderFactory;

class Page extends HeaderFactory
{
    public const GROUP_TITLE        = 'page-title';
    public const GROUP_CATEGORY     = 'page-category';
    public const GROUP_LAYOUT       = 'page-layout';
    public const GROUP_IS_PUBLISHED = 'page-isPublished';

    private const HEADER_TITLE        = 'website:pageTitle';
    private const HEADER_CATEGORY     = 'website:pageCategory';
    private const HEADER_LAYOUT       = 'website:pageLayout';
    private const HEADER_IS_PUBLISHED = 'website:pageIsPublished';

    /** @var array */
    protected $headers = [
        self::GROUP_TITLE        => self::HEADER_TITLE,
        self::GROUP_CATEGORY     => self::HEADER_CATEGORY,
        self::GROUP_LAYOUT       => self::HEADER_LAYOUT,
        self::GROUP_IS_PUBLISHED => self::HEADER_IS_PUBLISHED,
    ];

    /** @var array */
    protected $inputNames = [
        self::GROUP_TITLE => 'title',
    ];

    /** @var array */
    protected $fieldNames = [
        self::GROUP_TITLE => 'pages.title',
    ];
}
