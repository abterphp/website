<?php

declare(strict_types=1);

namespace AbterPhp\Website\Service\Execute;

use AbterPhp\Admin\Service\Execute\RepoServiceAbstract;
use AbterPhp\Framework\Constant\Session;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Website\Constant\Authorization;
use AbterPhp\Website\Domain\Entities\ContentList as Entity;
use AbterPhp\Website\Domain\Entities\ContentListItem as Item;
use AbterPhp\Website\Orm\ContentListRepo as GridRepo;
use AbterPhp\Website\Validation\Factory\ContentList as ValidatorFactory;
use Casbin\Enforcer;
use Cocur\Slugify\Slugify;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Http\Requests\UploadedFile;
use Opulence\Orm\IUnitOfWork;
use Opulence\Sessions\ISession;

class ContentList extends RepoServiceAbstract
{
    /** @var Slugify */
    protected $slugify;

    /** @var GridRepo */
    protected $repo;

    /** @var ISession */
    protected $session;

    /** @var Enforcer */
    protected $enforcer;

    /**
     * ContentList constructor.
     *
     * @param GridRepo         $repo
     * @param ValidatorFactory $validatorFactory
     * @param IUnitOfWork      $unitOfWork
     * @param IEventDispatcher $eventDispatcher
     * @param Slugify          $slugify
     * @param ISession         $session
     * @param Enforcer         $enforcer
     */
    public function __construct(
        GridRepo $repo,
        ValidatorFactory $validatorFactory,
        IUnitOfWork $unitOfWork,
        IEventDispatcher $eventDispatcher,
        Slugify $slugify,
        ISession $session,
        Enforcer $enforcer
    ) {
        parent::__construct($repo, $validatorFactory, $unitOfWork, $eventDispatcher);

        $this->slugify  = $slugify;
        $this->session  = $session;
        $this->enforcer = $enforcer;
    }

    /**
     * @param string $entityId
     *
     * @return Entity
     */
    public function createEntity(string $entityId): IStringerEntity
    {
        return new Entity($entityId, '', '', '', false, false, false, false, false, false);
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

        $postData = $this->protectPostData($entity, $postData);

        $name = (string)$postData['name'];

        $identifier = $postData['identifier'] ?? $entity->getIdentifier();
        $identifier = $identifier ?: $name;
        $identifier = $this->slugify->slugify($identifier);

        $classes = $postData['classes'];

        $protected      = !empty($postData['protected']);
        $withLinks      = !empty($postData['with_links']);
        $withLabelLinks = !empty($postData['with_label_links']);
        $withHtml       = !empty($postData['with_html']);
        $withImages     = !empty($postData['with_images']);
        $withClasses    = !empty($postData['with_classes']);

        $items = $this->createItems($postData, $entity->getId());

        $entity
            ->setIdentifier($identifier)
            ->setClasses($classes)
            ->setName($name)
            ->setProtected($protected)
            ->setWithLinks($withLinks)
            ->setWithLabelLinks($withLabelLinks)
            ->setWithHtml($withHtml)
            ->setWithImages($withImages)
            ->setWithClasses($withClasses)
            ->setItems($items);

        return $entity;
    }

    /**
     * @param Entity $entity
     * @param array  $postData
     *
     * @return array
     * @throws \Casbin\Exceptions\CasbinException
     */
    protected function protectPostData(Entity $entity, array $postData): array
    {
        if (!$entity->isProtected()) {
            return $postData;
        }

        $username        = $this->session->get(Session::USERNAME);
        $advancedAllowed = $this->enforcer->enforce(
            $username,
            Authorization::RESOURCE_LISTS,
            Authorization::ROLE_ADVANCED_WRITE
        );

        if ($advancedAllowed) {
            return $postData;
        }

        $postData['identifier']       = $entity->getIdentifier();
        $postData['protected']        = $entity->isProtected();
        $postData['with_links']       = $entity->isWithLinks();
        $postData['with_label_links'] = $entity->isWithLabelLinks();
        $postData['with_html']        = $entity->isWithHtml();
        $postData['with_images']      = $entity->isWithImages();
        $postData['with_classes']     = $entity->isWithClasses();

        return $postData;
    }

    /**
     * @param array  $postData
     * @param string $listId
     *
     * @return Item[]
     */
    protected function createItems(array $postData, string $listId): array
    {
        $items = [];

        $i = 1;
        while (isset($postData["new$i"])) {
            $itemData = $postData["new$i"];

            $i++;

            if (!empty($itemData['is_deleted'])) {
                continue;
            }

            $items[] = $this->createItem($itemData, $listId);
        }

        $i = 1;
        while (isset($postData["existing$i"])) {
            $items[] = $this->createItem($postData["existing$i"], $listId);

            $i++;
        }

        return $items;
    }

    /**
     * @param array  $itemData
     * @param string $listId
     *
     * @return Item
     */
    protected function createItem(array $itemData, string $listId): Item
    {
        $itemId      = $itemData['id'] ?? '';
        $label       = $itemData['label'] ?? '';
        $labelHref   = $itemData['label_href'] ?? '';
        $content     = $itemData['content'] ?? '';
        $contentHref = $itemData['content_href'] ?? '';
        $imgSrc      = $itemData['img_src'] ?? '';
        $imgAlt      = $itemData['img_alt'] ?? '';
        $imgHref     = $itemData['img_href'] ?? '';
        $classes     = $itemData['classes'] ?? '';
        $deleted     = !empty($itemData['is_deleted']);

        return new Item(
            $itemId,
            $listId,
            $label,
            $labelHref,
            $content,
            $contentHref,
            $imgSrc,
            $imgAlt,
            $imgHref,
            $classes,
            $deleted
        );
    }
}
