<?php

declare(strict_types=1);

namespace AbterPhp\Website\Service\RepoGrid;

use AbterPhp\Framework\Databases\Queries\FoundRows;
use AbterPhp\Framework\Http\Service\RepoGrid\RepoGridAbstract;
use AbterPhp\Website\Grid\Factory\BlockLayout as GridFactory;
use AbterPhp\Website\Orm\BlockLayoutRepo as Repo;
use Casbin\Enforcer;

class BlockLayout extends RepoGridAbstract
{
    /**
     * BlockLayout constructor.
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
