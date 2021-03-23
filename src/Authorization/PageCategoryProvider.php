<?php

declare(strict_types=1);

namespace AbterPhp\Website\Authorization;

use AbterPhp\Admin\Authorization\PolicyProviderTrait;
use AbterPhp\Admin\Databases\Queries\IAuthLoader;
use Casbin\Exceptions\CasbinException;
use Casbin\Model\Model;
use Casbin\Persist\Adapter as CasbinAdapter;

class PageCategoryProvider implements CasbinAdapter
{
    use PolicyProviderTrait;

    const PREFIX = 'page_category';

    /** @var IAuthLoader */
    protected IAuthLoader $authLoader;

    /**
     * PageCategoryProvider constructor.
     *
     * @param IAuthLoader $authLoader
     */
    public function __construct(IAuthLoader $authLoader)
    {
        $this->authLoader = $authLoader;
        $this->prefix     = static::PREFIX;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param Model $model
     */
    public function savePolicy(Model $model): void
    {
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param string $sec
     * @param string $ptype
     * @param array  $rule
     */
    public function addPolicy(string $sec, string $ptype, array $rule): void
    {
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param string $sec
     * @param string $ptype
     * @param array  $rule
     */
    public function removePolicy(string $sec, string $ptype, array $rule): void
    {
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param string $sec
     * @param string $ptype
     * @param int    $fieldIndex
     * @param string ...$fieldValues
     *
     * @throws CasbinException
     */
    public function removeFilteredPolicy(string $sec, string $ptype, int $fieldIndex, string ...$fieldValues): void
    {
        throw new CasbinException('not implemented');
    }
}
