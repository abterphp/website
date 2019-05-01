<?php

declare(strict_types=1);

namespace AbterPhp\Website\Service\RepoGrid;

use AbterPhp\Framework\Databases\Queries\FoundRows;
use AbterPhp\Framework\Http\Service\RepoGrid\RepoGridAbstract;
use AbterPhp\Website\Grid\Factory\PageCategory as GridFactory;
use AbterPhp\Website\Orm\PageCategoryRepo as Repo;
use Casbin\Enforcer;

class PageCategory extends RepoGridAbstract
{
    /**
     * PageCategory constructor.
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
