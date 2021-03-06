<?php

declare(strict_types=1);

namespace AbterPhp\Website\Service\Execute;

use AbterPhp\Admin\Service\Execute\RepoServiceAbstract;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Website\Domain\Entities\PageLayout as Entity;
use AbterPhp\Website\Domain\Entities\PageLayout\Assets;
use AbterPhp\Website\Orm\PageLayoutRepo as GridRepo;
use AbterPhp\Website\Validation\Factory\PageLayout as ValidatorFactory;
use Cocur\Slugify\Slugify;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Http\Requests\UploadedFile;
use Opulence\Orm\IUnitOfWork;

class PageLayout extends RepoServiceAbstract
{
    /** @var Slugify */
    protected $slugify;

    /** @var GridRepo */
    protected $repo;

    /**
     * PageLayout constructor.
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
        return new Entity($entityId, '', '', '', '', new Assets('', '', '', [], []));
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
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $name    = $postData['name'];
        $classes = $postData['classes'] ?? '';

        $identifier = $postData['identifier'] ?? $entity->getIdentifier();
        $identifier = $identifier ?: $name;
        $identifier = $this->slugify->slugify($identifier);

        $assets = $this->createAssets($postData, $identifier);

        $body = $postData['body'];

        $entity
            ->setName($name)
            ->setClasses($classes)
            ->setIdentifier($identifier)
            ->setBody($body)
            ->setAssets($assets);

        return $entity;
    }

    /**
     * @param array  $postData
     * @param string $identifier
     *
     * @return Assets
     */
    protected function createAssets(array $postData, string $identifier): Assets
    {
        $header   = $postData['header'] ?? '';
        $footer   = $postData['footer'] ?? '';
        $cssFiles = empty($postData['css-files']) ? [] : explode('\r\n', $postData['css-files']);
        $jsFiles  = empty($postData['js-files']) ? [] : explode('\r\n', $postData['js-files']);

        return new Assets(
            $identifier,
            $header,
            $footer,
            $cssFiles,
            $jsFiles
        );
    }
}
