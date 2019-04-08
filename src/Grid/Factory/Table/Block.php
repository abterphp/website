<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory\Table;

use AbterPhp\Framework\Grid\Factory\TableFactory;
use AbterPhp\Framework\Grid\Factory\Table\BodyFactory;
use AbterPhp\Website\Grid\Factory\Table\Header\Block as BlockHeaderFactory;

class Block extends TableFactory
{
    /**
     * Block constructor.
     *
     * @param BlockHeaderFactory $headerFactory
     * @param BodyFactory        $bodyFactory
     */
    public function __construct(BlockHeaderFactory $headerFactory, BodyFactory $bodyFactory)
    {
        parent::__construct($headerFactory, $bodyFactory);
    }
}
