<?php

declare(strict_types=1);

namespace AbterPhp\Website\Service\RepoGrid;

use AbterPhp\Admin\Service\RepoGrid\RepoGridAbstract;
use AbterPhp\Framework\Databases\Queries\FoundRows;
use AbterPhp\Website\Grid\Factory\ContentList as GridFactory;
use AbterPhp\Website\Orm\ContentListRepo as Repo;
use Casbin\Enforcer;

class ContentList extends RepoGridAbstract
{
    /**
     * ContentList constructor.
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
