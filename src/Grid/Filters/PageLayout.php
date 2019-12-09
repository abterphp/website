<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Filters;

use AbterPhp\Framework\Grid\Component\Filters;
use AbterPhp\Framework\Grid\Filter\ExactFilter;
use AbterPhp\Framework\Grid\Filter\LikeFilter;

class PageLayout extends Filters
{
    /**
     * PageLayout constructor.
     *
     * @param string[]    $intents
     * @param array       $attributes
     * @param string|null $tag
     */
    public function __construct(array $intents = [], array $attributes = [], ?string $tag = null)
    {
        parent::__construct($intents, $attributes, $tag);

        $this->nodes[] = new LikeFilter('name', 'website:pageLayoutName');

        $this->nodes[] = new ExactFilter('identifier', 'website:pageLayoutIdentifier');
    }
}
