<?php

declare(strict_types=1);

namespace AbterPhp\Website\Service\Execute;

use AbterPhp\Admin\Service\Execute\RepoServiceAbstract;
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
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
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

        $title = empty($postData['title']) ? '' : (string)$postData['title'];

        $identifier = empty($postData['identifier']) ? $title : (string)$postData['identifier'];
        $identifier = $this->slugify->slugify($identifier);

        $lead = empty($postData['lead']) ? '' : (string)$postData['lead'];
        $body = empty($postData['body']) ? '' : (string)$postData['body'];

        $isDraft = empty($postData['is_draft']);

        $category = null;
        if (!empty($postData['category_id'])) {
            $category = new PageCategory((string)$postData['category_id'], '', '');
        }

        $layoutId = empty($postData['layout_id']) ? null : (string)$postData['layout_id'];
        $layout   = empty($postData['layout']) || $layoutId !== null ? '' : (string)$postData['layout'];

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
     * @SuppressWarnings(PHPMD.NPathComplexity)
     *
     * @param array $postData
     *
     * @return Meta
     */
    protected function getMeta(array $postData): Meta
    {
        $description   = empty($postData['description']) ? '' : $postData['description'];
        $robots        = empty($postData['robots']) ? '' : $postData['robots'];
        $author        = empty($postData['author']) ? '' : $postData['author'];
        $copyright     = empty($postData['copyright']) ? '' : $postData['copyright'];
        $keywords      = empty($postData['keywords']) ? '' : $postData['keywords'];
        $ogTitle       = empty($postData['og-title']) ? '' : $postData['og-title'];
        $ogImage       = empty($postData['og-image']) ? '' : $postData['og-image'];
        $ogDescription = empty($postData['og-description']) ? '' : $postData['og-description'];

        $entity = new Meta(
            $description,
            $robots,
            $author,
            $copyright,
            $keywords,
            $ogTitle,
            $ogImage,
            $ogDescription
        );

        return $entity;
    }

    /**
     * @SuppressWarnings(PHPMD.NPathComplexity)
     *
     * @param array $postData
     *
     * @return Assets
     */
    protected function getAssets(array $postData): Assets
    {
        $identifier = $postData['identifier'];
        $header     = empty($postData['header']) ? '' : $postData['header'];
        $footer     = empty($postData['footer']) ? '' : $postData['footer'];
        $cssFiles   = empty($postData['css-files']) ? [] : explode('\r\n', $postData['css-files']);
        $jsFiles    = empty($postData['js-files']) ? [] : explode('\r\n', $postData['js-files']);

        return new Assets(
            $identifier,
            $header,
            $footer,
            $cssFiles,
            $jsFiles,
            null
        );
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
