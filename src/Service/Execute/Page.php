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
        return new Entity($entityId, '', '', '', '', '', false);
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
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $title = $postData['title'];

        $identifier = $postData['identifier'] ?? $entity->getIdentifier();
        $identifier = $identifier ?: $title;
        $identifier = $this->slugify->slugify($identifier);

        $classes = $postData['classes'];
        $lead    = $postData['lead'];
        $body    = $postData['body'];

        $isDraft = !empty($postData['is_draft']);

        $category = null;
        if (!empty($postData['category_id'])) {
            $category = new PageCategory($postData['category_id'], '', '');
        }

        $layoutId = $postData['layout_id'];
        $layout   = '';
        if (empty($layoutId)) {
            $layoutId = null;
            $layout   = $postData['layout'] ?? '';
        }

        $meta   = $this->getMeta($postData);
        $assets = $this->getAssets($postData, $identifier);

        $entity
            ->setIdentifier($identifier)
            ->setTitle($title)
            ->setClasses($classes)
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
        $description   = $postData['description'] ?? '';
        $robots        = $postData['robots'] ?? '';
        $author        = $postData['author'] ?? '';
        $copyright     = $postData['copyright'] ?? '';
        $keywords      = $postData['keywords'] ?? '';
        $ogTitle       = $postData['og-title'] ?? '';
        $ogImage       = $postData['og-image'] ?? '';
        $ogDescription = $postData['og-description'] ?? '';

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
     * @param array  $postData
     * @param string $identifier
     *
     * @return Assets
     */
    protected function getAssets(array $postData, string $identifier): Assets
    {
        $header   = empty($postData['header']) ? '' : $postData['header'];
        $footer   = empty($postData['footer']) ? '' : $postData['footer'];
        $cssFiles = empty($postData['css-files']) ? [] : explode('\r\n', $postData['css-files']);
        $jsFiles  = empty($postData['js-files']) ? [] : explode('\r\n', $postData['js-files']);

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
