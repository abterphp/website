<?php

declare(strict_types=1);

namespace AbterPhp\Website\Service\Execute;

use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Http\Service\Execute\RepoServiceAbstract;
use AbterPhp\Website\Domain\Entities\PageCategory as Entity;
use AbterPhp\Website\Orm\PageCategoryRepo as GridRepo;
use AbterPhp\Website\Validation\Factory\PageCategory as ValidatorFactory;
use Cocur\Slugify\Slugify;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Http\Requests\UploadedFile;
use Opulence\Orm\IUnitOfWork;

class PageCategory extends RepoServiceAbstract
{
    /** @var Slugify */
    protected $slugify;

    /**
     * PageCategory constructor.
     *
     * @param GridRepo         $repo
     * @param ValidatorFactory $validatorFactory
     * @param IUnitOfWork      $unitOfWork
     * @param IEventDispatcher $eventDispatcher
     * @param Slugify          $slugify
     */
    public function __construct(
        GridRepo $repo,
        ValidatorFactory $validatorFactory,
        IUnitOfWork $unitOfWork,
        IEventDispatcher $eventDispatcher,
        Slugify $slugify
    ) {
        parent::__construct($repo, $validatorFactory, $unitOfWork, $eventDispatcher);

        $this->slugify = $slugify;
    }


    /**
     * @param string $entityId
     *
     * @return Entity
     */
    public function createEntity(string $entityId): IStringerEntity
    {
        return new Entity($entityId, '', '');
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param Entity         $entity
     * @param array          $postData
     * @param UploadedFile[] $fileData
     *
     * @return Entity
     */
    protected function fillEntity(IStringerEntity $entity, array $postData, array $fileData): IStringerEntity
    {
        $name = (string)$postData['name'];

        $identifier = (string)$postData['identifier'];
        if (empty($identifier)) {
            $identifier = $name;
        }
        $identifier = $this->slugify->slugify($identifier);

        $userGroups = [];
        if (array_key_exists('user_group_ids', $postData)) {
            foreach ($postData['user_group_ids'] as $id) {
                $userGroups[] = new UserGroup((string)$id, '', '');
            }
        }

        $entity
            ->setName($name)
            ->setIdentifier($identifier)
            ->setUserGroups($userGroups);

        return $entity;
    }
}
