<?php

declare(strict_types=1);

namespace AbterPhp\Website\Events;

use Opulence\Views\IView;

class WebsiteReady
{
    /** @var IView */
    private $view;

    /**
     * WebsiteReady constructor.
     *
     * @param IView $view
     */
    public function __construct(IView $view)
    {
        $this->view = $view;
    }

    /**
     * @return IView
     */
    public function getView(): IView
    {
        return $this->view;
    }
}
