<?php

declare(strict_types=1);

namespace AbterPhp\Website\Service\Execute;

use AbterPhp\Admin\Http\Service\Execute\RepoServiceAbstract;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Website\Domain\Entities\Page as Entity;
use AbterPhp\Website\Domain\Entities\Page\Assets;
use AbterPhp\Website\Domain\Entities\Page\Meta;
use AbterPhp\Website\Domain\Entities\PageCategory;
use AbterPhp\Website\Orm\PageRepo as GridRepo;
use AbterPhp\Website\Validation\Factory\Page as ValidatorFactory;
use Cocur\Slugify\Slugify;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Http\Requests\UploadedFile;
use Opulence\Orm\IUnitOfWork;

class Page extends RepoServiceAbstract
{
    /** @var Slugify */
    protected $slugify;

    /** @var GridRepo */
    protected $repo;

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
    public function createEntity(string $entityId): IStringerEntity
    {
        return new Entity($entityId, '', '', '', '', false);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param IStringerEntity $entity
     * @param array           $postData
     * @param UploadedFile[]  $fileData
     *
     * @return Entity
     */
    protected function fillEntity(IStringerEntity $entity, array $postData, array $fileData): IStringerEntity
    {
        if (!($entity instanceof Entity)) {
            throw new \InvalidArgumentException('Not a page...');
        }

        $title = (string)$postData['title'];

        $identifier = (string)$postData['identifier'];
        if (empty($identifier)) {
            $identifier = $title;
        }
        $identifier = $this->slugify->slugify($identifier);

        $lead = (string)$postData['lead'];
        $body = (string)$postData['body'];

        $isDraft = false;
        if (!empty($postData['is_draft'])) {
            $isDraft = (bool)$postData['is_draft'];
        }

        $category = null;
        if (!empty($postData['category_id'])) {
            $category = new PageCategory((string)$postData['category_id'], '', '');
        }

        $layoutId = (string)$postData['layout_id'];
        $layout   = '';
        if (!$layoutId) {
            $layoutId = null;
            $layout   = (string)$postData['layout'];
        }

        $meta   = $this->getMeta($postData);
        $assets = $this->getAssets($postData);

        $entity
            ->setIdentifier($identifier)
            ->setTitle($title)
            ->setLead($lead)
            ->setBody($body)
            ->setIsDraft($isDraft)
            ->setCategory($category)
            ->setLayoutId($layoutId)
            ->setLayout($layout)
            ->setMeta($meta)
            ->setAssets($assets);

        return $entity;
    }

    /**
     * @param array $postData
     *
     * @return Meta
     */
    protected function getMeta(array $postData): Meta
    {
        $entity = new Meta(
            $postData['description'],
            $postData['robots'],
            $postData['author'],
            $postData['copyright'],
            $postData['keywords'],
            $postData['og-title'],
            $postData['og-image'],
            $postData['og-description']
        );

        return $entity;
    }

    /**
     * @param array $postData
     *
     * @return Assets
     */
    protected function getAssets(array $postData): Assets
    {
        if (is_string($postData['css-files'])) {
            $postData['css-files'] = explode('\r\n', $postData['css-files']);
        }
        if (is_string($postData['js-files'])) {
            $postData['js-files'] = explode('\r\n', $postData['js-files']);
        }

        $entity = new Assets(
            $postData['identifier'],
            $postData['header'],
            $postData['footer'],
            $postData['css-files'],
            $postData['js-files'],
            null
        );

        return $entity;
    }

    /**
     * @param string $entityId
     *
     * @return IStringerEntity
     * @throws \Opulence\Orm\OrmException
     */
    public function retrieveEntityWithLayout(string $entityId): IStringerEntity
    {
        $entity = $this->repo->getById($entityId);

        $entity = $this->repo->getWithLayout($entity->getIdentifier());

        return $entity;
    }
}
