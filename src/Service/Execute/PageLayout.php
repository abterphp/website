<?php

declare(strict_types=1);

namespace AbterPhp\Website\Service\Execute;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Http\Service\Execute\RepoServiceAbstract;
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
        return new Entity($entityId, '', '', new Assets('', '', '', [], []));
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
        $identifier = $this->slugify->slugify((string)$postData['identifier']);

        $assets = $this->createAssets($postData);

        $entity
            ->setIdentifier($identifier)
            ->setBody((string)$postData['body'])
            ->setAssets($assets)
        ;

        return $entity;
    }

    /**
     * @param array $postData
     *
     * @return Assets
     */
    protected function createAssets(array $postData): Assets
    {
        if (is_string($postData['css-files'])) {
            $postData['css-files'] = explode('\r\n', $postData['css-files']);
        }

        if (is_string($postData['js-files'])) {
            $postData['js-files'] = explode('\r\n', $postData['js-files']);
        }

        return new Assets(
            $postData['identifier'],
            $postData['header'],
            $postData['footer'],
            $postData['css-files'],
            $postData['js-files']
        );
    }
}
