<?php

declare(strict_types=1);

namespace AbterPhp\Website\Service\Execute;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Http\Service\Execute\RepoServiceAbstract;
use AbterPhp\Website\Domain\Entities\Block as Entity;
use AbterPhp\Website\Orm\BlockRepo as GridRepo;
use AbterPhp\Website\Validation\Factory\Block as ValidatorFactory;
use Cocur\Slugify\Slugify;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Orm\IUnitOfWork;

class Block extends RepoServiceAbstract
{
    /** @var Slugify */
    protected $slugify;

    /**
     * Block constructor.
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
    protected function createEntity(string $entityId): IStringerEntity
    {
        return new Entity($entityId, '', '', '', '', null);
    }

    /**
     * @param Entity $entity
     * @param array  $data
     *
     * @return Entity
     */
    protected function fillEntity(IStringerEntity $entity, array $data): IStringerEntity
    {
        $body  = (string)$data['body'];
        $title = (string)$data['title'];

        $identifier = (string)$data['identifier'];
        if (empty($identifier)) {
            $identifier = $title;
        }
        $identifier = $this->slugify->slugify($identifier);

        $layoutId = null;
        $layout   = (string)$data['layout'];
        if (!$layout) {
            $layoutId = (string)$data['layout_id'];
        }

        $entity
            ->setIdentifier($identifier)
            ->setTitle($title)
            ->setBody($body)
            ->setLayoutId($layoutId)
            ->setLayout($layout)
        ;

        return $entity;
    }
}
