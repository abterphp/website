<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory\Table;

use AbterPhp\Admin\Grid\Factory\Table\BodyFactory;
use AbterPhp\Admin\Grid\Factory\TableFactory;
use AbterPhp\Website\Grid\Factory\Table\Header\ContentList as HeaderFactory;

class ContentList extends TableFactory
{
    /**
     * ContentList constructor.
     *
     * @param HeaderFactory $headerFactory
     * @param BodyFactory   $bodyFactory
     */
    public function __construct(HeaderFactory $headerFactory, BodyFactory $bodyFactory)
    {
        parent::__construct($headerFactory, $bodyFactory);
    }
}
