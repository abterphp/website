<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory;

use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Admin\Form\Factory\Base;
use AbterPhp\Admin\Orm\UserGroupRepo;
use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Form\Component\Option;
use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Element\MultiSelect;
use AbterPhp\Framework\Form\Element\Select;
use AbterPhp\Framework\Form\IForm;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Website\Domain\Entities\PageCategory as Entity;
use Opulence\Orm\IEntity;
use Opulence\Sessions\ISession;

class PageCategory extends Base
{
    /** @var UserGroupRepo */
    protected $userGroupRepo;

    /**
     * PageCategory constructor.
     *
     * @param ISession      $session
     * @param ITranslator   $translator
     * @param UserGroupRepo $userGroupRepo
     */
    public function __construct(ISession $session, ITranslator $translator, UserGroupRepo $userGroupRepo)
    {
        parent::__construct($session, $translator);

        $this->userGroupRepo = $userGroupRepo;
    }

    /**
     * @param string       $action
     * @param string       $method
     * @param string       $showUrl
     * @param IEntity|null $entity
     *
     * @return IForm
     */
    public function create(string $action, string $method, string $showUrl, ?IEntity $entity = null): IForm
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $this->createForm($action, $method)
            ->addDefaultElements()
            ->addIdentifier($entity)
            ->addName($entity)
            ->addUserGroups($entity)
            ->addDefaultButtons($showUrl);

        $form = $this->form;

        $this->form = null;

        return $form;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addIdentifier(Entity $entity): PageCategory
    {
        $input = new Input('identifier', 'identifier', $entity->getIdentifier());
        $label = new Label('identifier', 'website:pageCategoryIdentifier');

        $this->form[] = new FormGroup($input, $label, null);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addName(Entity $entity): PageCategory
    {
        $input = new Input('name', 'name', $entity->getIdentifier());
        $label = new Label('name', 'website:pageCategoryName');

        $this->form[] = new FormGroup($input, $label);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addUserGroups(Entity $entity): PageCategory
    {
        $allUserGroups = $this->getAllUserGroups();
        $userGroupIds  = $this->getUserGroupIds($entity);

        $options = $this->createUserGroupOptions($allUserGroups, $userGroupIds);

        $this->form[] = new FormGroup(
            $this->createUserGroupSelect($options),
            $this->createUserGroupLabel()
        );

        return $this;
    }

    /**
     * @return UserGroup[]
     * @throws \Opulence\Orm\OrmException
     */
    protected function getAllUserGroups(): array
    {
        return $this->userGroupRepo->getAll();
    }

    /**
     * @param Entity $entity
     *
     * @return int[]
     */
    protected function getUserGroupIds(Entity $entity): array
    {
        $userGroupIds = [];
        foreach ($entity->getUserGroups() as $userGroup) {
            $userGroupIds[] = $userGroup->getId();
        }

        return $userGroupIds;
    }

    /**
     * @param UserGroup[] $allUserGroups
     * @param int[]       $userGroupIds
     *
     * @return Option[]
     */
    protected function createUserGroupOptions(array $allUserGroups, array $userGroupIds): array
    {
        $options = [];
        foreach ($allUserGroups as $userGroup) {
            $isSelected = in_array($userGroup->getId(), $userGroupIds, true);
            $options[]  = new Option((string)$userGroup->getId(), $userGroup->getName(), $isSelected);
        }

        return $options;
    }

    /**
     * @param Option[] $options
     *
     * @return Select
     */
    protected function createUserGroupSelect(array $options): Select
    {
        $attributes = [
            Html5::ATTR_SIZE => $this->getMultiSelectSize(
                count($options),
                static::MULTISELECT_MIN_SIZE,
                static::MULTISELECT_MAX_SIZE
            ),
        ];

        $select = new MultiSelect('user_group_ids', 'user_group_ids[]', [], $attributes);

        foreach ($options as $option) {
            $select[] = $option;
        }

        return $select;
    }

    /**
     * @return Label
     */
    protected function createUserGroupLabel(): Label
    {
        return new Label('user_group_ids', 'website:pageCategoryUserGroups');
    }

    /**
     * @param int $optionCount
     * @param int $minSize
     * @param int $maxSize
     *
     * @return int
     */
    protected function getMultiSelectSize(int $optionCount, int $minSize, int $maxSize): int
    {
        return (int)max(min($optionCount, $maxSize), $minSize);
    }
}
