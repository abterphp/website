<?php

declare(strict_types=1);

namespace AbterPhp\Website\Service\Execute;

use AbterPhp\Admin\Service\Execute\RepoServiceAbstract;
use AbterPhp\Framework\Constant\Session;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Website\Constant\Authorization;
use AbterPhp\Website\Domain\Entities\ContentList as Entity;
use AbterPhp\Website\Domain\Entities\ContentListItem as Item;
use AbterPhp\Website\Domain\Entities\ContentListType as Type;
use AbterPhp\Website\Orm\ContentListRepo as GridRepo;
use AbterPhp\Website\Validation\Factory\ContentList as ValidatorFactory;
use Casbin\Enforcer;
use Cocur\Slugify\Slugify;
use DateTime;
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
        return new Entity($entityId, '', '', '', false, false, false, false, false);
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

        $type = new Type((string)$postData['type_id'], '', '');

        $name = (string)$postData['name'];

        $identifier = empty($postData['identifier']) ? $name : (string)$postData['identifier'];
        $identifier = $this->slugify->slugify($identifier);

        $classes = $postData['classes'];

        $protected = empty($postData['protected']) ? false : (bool)$postData['protected'];
        $withImage = empty($postData['with_image']) ? false : (bool)$postData['with_image'];
        $withLinks = empty($postData['with_links']) ? false : (bool)$postData['with_links'];
        $withHtml  = empty($postData['with_html']) ? false : (bool)$postData['with_html'];

        $entity
            ->setType($type)
            ->setIdentifier($identifier)
            ->setClasses($classes)
            ->setName($name)
            ->setProtected($protected)
            ->setWithImage($withImage)
            ->setWithLinks($withLinks)
            ->setWithHtml($withHtml)
            ->setItems($this->createItems($postData, $entity->getId()));

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

        $postData['type_id']    = $entity->getType()->getId();
        $postData['identifier'] = $entity->getIdentifier();
        $postData['protected']  = $entity->isProtected();
        $postData['with_image'] = $entity->isWithImage();
        $postData['with_links'] = $entity->isWithLinks();
        $postData['with_html']  = $entity->isWithHtml();

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
            $d = $postData["new$i"];

            $i++;

            if (!empty($d['is_deleted'])) {
                continue;
            }

            $items[] = new Item(
                '',
                $listId,
                $d['name'],
                $d['name_href'],
                $d['body'],
                $d['body_href'],
                $d['img_src'],
                $d['img_alt'],
                $d['img_href']
            );
        }

        $i = 1;
        while (isset($postData["existing$i"])) {
            $d = $postData["existing$i"];

            $deletedAt = empty($d['is_deleted']) ? null : new DateTime();

            $items[] = new Item(
                $d['id'],
                $listId,
                $d['name'],
                $d['name_href'],
                $d['body'],
                $d['body_href'],
                $d['img_src'],
                $d['img_alt'],
                $d['img_href'],
                $deletedAt
            );

            $i++;
        }

        return $items;
    }
}
