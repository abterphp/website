<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory\Table;

use AbterPhp\Admin\Grid\Factory\TableFactory;
use AbterPhp\Admin\Grid\Factory\Table\BodyFactory;
use AbterPhp\Website\Grid\Factory\Table\Header\PageCategory as HeaderFactory;

class PageCategory extends TableFactory
{
    /**
     * PageCategory constructor.
     *
     * @param HeaderFactory $headerFactory
     * @param BodyFactory   $bodyFactory
     */
    public function __construct(HeaderFactory $headerFactory, BodyFactory $bodyFactory)
    {
        parent::__construct($headerFactory, $bodyFactory);
    }
}
