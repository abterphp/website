<?php

declare(strict_types=1);

namespace AbterPhp\Website\Service\Execute;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Http\Service\Execute\RepoServiceAbstract;
use AbterPhp\Website\Domain\Entities\Page as Entity;
use AbterPhp\Website\Domain\Entities\Page\Assets;
use AbterPhp\Website\Domain\Entities\Page\Meta;
use AbterPhp\Website\Orm\PageRepo as GridRepo;
use AbterPhp\Website\Validation\Factory\Page as ValidatorFactory;
use Cocur\Slugify\Slugify;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Orm\IUnitOfWork;

class Page extends RepoServiceAbstract
{
    /** @var Slugify */
    protected $slugify;

    /**
     * Page constructor.
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
        return new Entity($entityId, '', '', '', '', '', null);
    }

    /**
     * @param Entity $entity
     * @param array  $data
     *
     * @return Entity
     */
    protected function fillEntity(IStringerEntity $entity, array $data): IStringerEntity
    {
        if (!($entity instanceof Entity)) {
            throw new \InvalidArgumentException('Not a page...');
        }

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

        $meta = $this->getMeta($data);
        $assets = $this->getAssets($data);

        $entity
            ->setIdentifier($identifier)
            ->setTitle($title)
            ->setBody($body)
            ->setLayoutId($layoutId)
            ->setLayout($layout)
            ->setMeta($meta)
            ->setAssets($assets)
        ;

        return $entity;
    }

    /**
     * @param array $data
     *
     * @return Meta
     */
    protected function getMeta(array $data): Meta
    {
        $entity = new Meta(
            $data['description'],
            $data['robots'],
            $data['author'],
            $data['copyright'],
            $data['keywords'],
            $data['og-title'],
            $data['og-image'],
            $data['og-description']
        );

        return $entity;
    }

    /**
     * @param array $data
     *
     * @return Assets
     */
    protected function getAssets(array $data): Assets
    {
        $entity = new Assets(
            $data['identifier'],
            $data['header'],
            $data['footer'],
            explode('\r\n', $data['css-files']),
            explode('\r\n', $data['js-files']),
            null
        );

        return $entity;
    }
}
