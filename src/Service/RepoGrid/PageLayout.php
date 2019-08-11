<?php

declare(strict_types=1);

namespace AbterPhp\Website\Service\RepoGrid;

use AbterPhp\Admin\Http\Service\RepoGrid\RepoGridAbstract;
use AbterPhp\Framework\Databases\Queries\FoundRows;
use AbterPhp\Website\Grid\Factory\PageLayout as GridFactory;
use AbterPhp\Website\Orm\PageLayoutRepo as Repo;
use Casbin\Enforcer;

class PageLayout extends RepoGridAbstract
{
    /**
     * PageLayout constructor.
     *
     * @param Enforcer    $enforcer
     * @param Repo        $repo
     * @param FoundRows   $foundRows
     * @param GridFactory $gridFactory
     */
    public function __construct(Enforcer $enforcer, Repo $repo, FoundRows $foundRows, GridFactory $gridFactory)
    {
        parent::__construct($enforcer, $repo, $foundRows, $gridFactory);
    }
}
