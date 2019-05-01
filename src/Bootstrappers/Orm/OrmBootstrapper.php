<?php

declare(strict_types=1);

namespace AbterPhp\Website\Bootstrappers\Orm;

use AbterPhp\Admin\Bootstrappers\Orm\OrmBootstrapper as AbterAdminOrmBootstrapper;
use AbterPhp\Website\Domain\Entities\Block;
use AbterPhp\Website\Domain\Entities\BlockLayout;
use AbterPhp\Website\Domain\Entities\Page;
use AbterPhp\Website\Domain\Entities\PageCategory;
use AbterPhp\Website\Domain\Entities\PageLayout;
use AbterPhp\Website\Orm\BlockLayoutRepo;
use AbterPhp\Website\Orm\BlockRepo;
use AbterPhp\Website\Orm\DataMappers\BlockLayoutSqlDataMapper;
use AbterPhp\Website\Orm\DataMappers\BlockSqlDataMapper;
use AbterPhp\Website\Orm\DataMappers\PageCategorySqlDataMapper;
use AbterPhp\Website\Orm\DataMappers\PageLayoutSqlDataMapper;
use AbterPhp\Website\Orm\DataMappers\PageSqlDataMapper;
use AbterPhp\Website\Orm\PageCategoryRepo;
use AbterPhp\Website\Orm\PageLayoutRepo;
use AbterPhp\Website\Orm\PageRepo;
use Opulence\Ioc\IContainer;
use Opulence\Ioc\IocException;
use Opulence\Orm\IUnitOfWork;
use RuntimeException;

class OrmBootstrapper extends AbterAdminOrmBootstrapper
{
    /** @var array */
    protected $repoMappers = [
        BlockLayoutRepo::class  => [BlockLayoutSqlDataMapper::class, BlockLayout::class],
        BlockRepo::class        => [BlockSqlDataMapper::class, Block::class],
        PageLayoutRepo::class   => [PageLayoutSqlDataMapper::class, PageLayout::class],
        PageCategoryRepo::class => [PageCategorySqlDataMapper::class, PageCategory::class],
        PageRepo::class         => [PageSqlDataMapper::class, Page::class],
    ];

    /**
     * @inheritdoc
     */
    public function getBindings(): array
    {
        return array_keys($this->repoMappers);
    }

    /**
     * @inheritdoc
     */
    public function registerBindings(IContainer $container)
    {
        try {
            $unitOfWork = $container->resolve(IUnitOfWork::class);
            $this->bindRepositories($container, $unitOfWork);
        } catch (IocException $ex) {
            $namespace = explode('\\', __NAMESPACE__)[0];
            throw new RuntimeException("Failed to register $namespace bindings", 0, $ex);
        }
    }
}
