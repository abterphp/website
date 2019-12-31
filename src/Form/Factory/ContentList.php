<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory;

use AbterPhp\Admin\Form\Factory\Base;
use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Constant\Session;
use AbterPhp\Framework\Form\Component\Option;
use AbterPhp\Framework\Form\Container\CheckboxGroup;
use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Element\Select;
use AbterPhp\Framework\Form\Extra\DefaultButtons;
use AbterPhp\Framework\Form\Extra\Help;
use AbterPhp\Framework\Form\IForm;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\Html\Component;
use AbterPhp\Framework\Html\Component\Button;
use AbterPhp\Framework\Html\Tag;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Website\Constant\Authorization;
use AbterPhp\Website\Domain\Entities\ContentList as Entity;
use AbterPhp\Website\Domain\Entities\ContentListItem; // @phan-suppress-current-line PhanUnreferencedUseNormal
use AbterPhp\Website\Domain\Entities\ContentListType;
use AbterPhp\Website\Form\Factory\ContentList\Item;
use AbterPhp\Website\Orm\ContentListItemRepo as ItemRepo;
use AbterPhp\Website\Orm\ContentListTypeRepo as TypeRepo;
use Casbin\Enforcer;
use Opulence\Orm\IEntity;
use Opulence\Sessions\ISession;

class ContentList extends Base
{
    private const NEW_ITEM_NAME = 'website:contentListItemNew';

    private const ITEM_TEMPLATE_CLASS         = 'item-template';
    private const NEW_ITEMS_CONTAINER_ID      = 'new-items';
    private const EXISTING_ITEMS_CONTAINER_ID = 'existing-items';

    /** @var TypeRepo */
    protected $typeRepo;

    /** @var ItemRepo */
    protected $itemRepo;

    /** @var Enforcer */
    protected $enforcer;

    /** @var bool */
    private $isNew = false;

    /** @var bool|null */
    protected $advancedAllowed;

    /**
     * ContentList constructor.
     *
     * @param ISession    $session
     * @param ITranslator $translator
     * @param TypeRepo    $typeRepo
     * @param ItemRepo    $itemRepo
     * @param Enforcer    $enforcer
     */
    public function __construct(
        ISession $session,
        ITranslator $translator,
        TypeRepo $typeRepo,
        ItemRepo $itemRepo,
        Enforcer $enforcer
    ) {
        parent::__construct($session, $translator);

        $this->typeRepo = $typeRepo;
        $this->itemRepo = $itemRepo;
        $this->enforcer = $enforcer;
    }

    /**
     * @return bool
     * @throws \Casbin\Exceptions\CasbinException
     */
    protected function isAdvancedAllowed(): bool
    {
        if ($this->advancedAllowed !== null) {
            return $this->advancedAllowed;
        }

        $username              = $this->session->get(Session::USERNAME);
        $this->advancedAllowed = $this->enforcer->enforce(
            $username,
            Authorization::RESOURCE_LISTS,
            Authorization::ROLE_ADVANCED_WRITE
        );

        return $this->advancedAllowed;
    }

    /**
     * @param string       $action
     * @param string       $method
     * @param string       $showUrl
     * @param IEntity|null $entity
     *
     * @return IForm
     * @throws \Casbin\Exceptions\CasbinException
     */
    public function create(string $action, string $method, string $showUrl, ?IEntity $entity = null): IForm
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $this->isNew = ($entity->getName() == '');

        $this->createForm($action, $method)
            ->addDefaultElements()
            ->addTypeId($entity)
            ->addName($entity)
            ->addIdentifier($entity)
            ->addProtected($entity)
            ->addWithImage($entity)
            ->addWithLinks($entity)
            ->addWithHtml($entity)
            ->addExistingItems($entity)
            ->addNewItems($entity)
            ->addAddBtn($entity)
            ->addButtons($entity, $showUrl);

        $form = $this->form;

        $this->form = null;

        return $form;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addTypeId(Entity $entity): ContentList
    {
        // Type of protected lists can only be set by advanced users
        if ($entity->isProtected() && !$this->isAdvancedAllowed()) {
            return $this;
        }

        $allTypes = $this->getAllTypes();
        $typeId   = $entity->getTypeId();

        $options = $this->createTypeIdOptions($allTypes, $typeId);

        $this->form[] = new FormGroup(
            $this->createTypeIdSelect($options),
            $this->createTypeIdLabel()
        );

        return $this;
    }

    /**
     * @return ContentListType[]
     */
    protected function getAllTypes(): array
    {
        return $this->typeRepo->getAll();
    }

    /**
     * @param ContentListType[] $allTypes
     * @param string            $typeId
     *
     * @return Option[]
     */
    protected function createTypeIdOptions(array $allTypes, string $typeId): array
    {
        $options   = [];
        $options[] = new Option('', 'framework:none', false);
        foreach ($allTypes as $layout) {
            $isSelected = $layout->getId() === $typeId;
            $options[]  = new Option($layout->getId(), $layout->getLabel(), $isSelected);
        }

        return $options;
    }

    /**
     * @param Option[] $options
     *
     * @return Select
     */
    protected function createTypeIdSelect(array $options): Select
    {
        $select = new Select('type_id', 'type_id');

        foreach ($options as $option) {
            $select[] = $option;
        }

        return $select;
    }

    /**
     * @return Label
     */
    protected function createTypeIdLabel(): Label
    {
        return new Label('type_id', 'website:contentListTypeIdLabel');
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addName(Entity $entity): ContentList
    {
        $input = new Input('name', 'name', $entity->getName());
        $label = new Label('name', 'website:contentListName');

        $this->form[] = new FormGroup($input, $label);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addIdentifier(Entity $entity): ContentList
    {
        // Identifier of protected lists can only be set by advanced users
        if ($entity->isProtected() && !$this->isAdvancedAllowed()) {
            return $this;
        }

        $input = new Input('identifier', 'identifier', $entity->getIdentifier());
        $label = new Label('identifier', 'website:contentListIdentifier');
        $help  = new Help('website:contentListIdentifierHelp');

        $this->form[] = new FormGroup($input, $label, $help);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addProtected(Entity $entity): ContentList
    {
        // Protected can not be set by non-advanced users
        if (!$this->isAdvancedAllowed()) {
            return $this;
        }

        $attributes = [Html5::ATTR_TYPE => Input::TYPE_CHECKBOX];
        if ($entity->isProtected()) {
            $attributes[Html5::ATTR_CHECKED] = null;
        }
        $input = new Input(
            'protected',
            'protected',
            '1',
            [],
            $attributes
        );
        $label = new Label('protected', 'website:contentListProtected');
        $help  = new Help('website:contentListProtectedHelp');

        $this->form[] = new CheckboxGroup($input, $label, $help, [], [Html5::ATTR_ID => 'protected-container']);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addWithImage(Entity $entity): ContentList
    {
        // With image setting of protected lists can only be modified by advanced users
        if ($entity->isProtected() && !$this->isAdvancedAllowed()) {
            return $this;
        }

        $attributes = [Html5::ATTR_TYPE => Input::TYPE_CHECKBOX];
        if ($entity->isWithImage()) {
            $attributes[Html5::ATTR_CHECKED] = null;
        }
        $input = new Input(
            'with_image',
            'with_image',
            '1',
            [],
            $attributes
        );
        $label = new Label('with_image', 'website:contentListWithImage');
        $help  = new Help('website:contentListWithImageHelp');

        $this->form[] = new CheckboxGroup($input, $label, $help, [], [Html5::ATTR_ID => 'withImage-container']);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addWithLinks(Entity $entity): ContentList
    {
        // With links setting of protected lists can only be modified by advanced users
        if ($entity->isProtected() && !$this->isAdvancedAllowed()) {
            return $this;
        }

        $attributes = [Html5::ATTR_TYPE => Input::TYPE_CHECKBOX];
        if ($entity->isWithLinks()) {
            $attributes[Html5::ATTR_CHECKED] = null;
        }
        $input = new Input(
            'with_links',
            'with_links',
            '1',
            [],
            $attributes
        );
        $label = new Label('with_links', 'website:contentListWithLinks');
        $help  = new Help('website:contentListWithLinksHelp');

        $this->form[] = new CheckboxGroup($input, $label, $help, [], [Html5::ATTR_ID => 'withLinks-container']);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addWithHtml(Entity $entity): ContentList
    {
        // With HTML setting of protected lists can only be modified by advanced users
        if ($entity->isProtected() && !$this->isAdvancedAllowed()) {
            return $this;
        }

        $attributes = [Html5::ATTR_TYPE => Input::TYPE_CHECKBOX];
        if ($entity->isWithHtml()) {
            $attributes[Html5::ATTR_CHECKED] = null;
        }
        $input = new Input(
            'with_html',
            'with_html',
            '1',
            [],
            $attributes
        );
        $label = new Label('with_html', 'website:contentListWithHtml');
        $help  = new Help('website:contentListWithHtmlHelp');

        $this->form[] = new CheckboxGroup($input, $label, $help, [], [Html5::ATTR_ID => 'withHtml-container']);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return ContentList
     */
    protected function addExistingItems(Entity $entity): ContentList
    {
        // There's no reason to check existing items during creation
        if (!$entity->getId()) {
            return $this;
        }

        $containerAttribs = [Html5::ATTR_ID => static::EXISTING_ITEMS_CONTAINER_ID];
        $container        = new Component(null, [], $containerAttribs, Html5::TAG_SECTION);

        $itemFormFactory = new Item($entity->isProtected(), $entity->isWithImage(), $entity->isWithLinks());

        /** @var ContentListItem[] $items */
        $items = $this->itemRepo->getByListId($entity->getId());
        foreach ($items as $item) {
            $fieldset   = new Component(null, [], [], Html5::TAG_FIELDSET);
            $fieldset[] = new Tag($item->getName(), [], [], Html5::TAG_LEGEND);
            foreach ($itemFormFactory->create($item) as $node) {
                $fieldset[] = $node;
            }
            $container[] = $fieldset;
        }

        $this->form[] = $container;

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return ContentList
     */
    protected function addNewItems(Entity $entity): ContentList
    {
        // New items can not be added during creation
        if (!$entity->getId()) {
            return $this;
        }
        // New items can only be added to protected lists by advanced users
        if ($entity->isProtected() && !$this->isAdvancedAllowed()) {
            return $this;
        }

        $containerAttribs = [Html5::ATTR_ID => static::NEW_ITEMS_CONTAINER_ID];
        $container        = new Component(null, [], $containerAttribs, Html5::TAG_SECTION);

        $itemAttribs     = [Html5::ATTR_CLASS => static::ITEM_TEMPLATE_CLASS];
        $item            = new Component(null, [], $itemAttribs, Html5::TAG_FIELDSET);
        $item[]          = new Tag(static::NEW_ITEM_NAME, [], [], Html5::TAG_LEGEND);
        $itemFormFactory = new Item($entity->isProtected(), $entity->isWithImage(), $entity->isWithLinks());
        foreach ($itemFormFactory->create() as $component) {
            $item[] = $component;
        }

        $container[] = $item;

        $this->form[] = $container;

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return ContentList
     */
    protected function addAddBtn(Entity $entity): ContentList
    {
        // New items can not be added during creation
        if (!$entity->getId()) {
            return $this;
        }
        // New items can only be added to protected lists by advanced users
        if ($entity->isProtected() && !$this->isAdvancedAllowed()) {
            return $this;
        }

        $i   = new Component('add', [Component::INTENT_SMALL, Component::INTENT_ICON], [], Html5::TAG_I);
        $btn = new Button($i, [Button::INTENT_FAB, Button::INTENT_PRIMARY], [Html5::ATTR_TYPE => Button::TYPE_BUTTON]);

        $this->form[] = new Component($btn, [], [Html5::ATTR_ID => 'add-item-container'], Html5::TAG_DIV);

        return $this;
    }

    /**
     * @param string $showUrl
     *
     * @return Base
     */
    protected function addButtons(Entity $entity, string $showUrl): Base
    {
        if ($entity->getName()) {
            return parent::addDefaultButtons($showUrl);
        }

        return $this->addNewButtons($showUrl);
    }

    /**
     * @param string $showUrl
     *
     * @return Base
     */
    protected function addNewButtons(string $showUrl): Base
    {
        $buttons = new DefaultButtons();

        $buttons
            ->addSaveAndEdit(Button::INTENT_PRIMARY, Button::INTENT_FORM)
            ->addBackToGrid($showUrl)
            ->addSaveAndBack(Button::INTENT_WARNING, Button::INTENT_FORM)
            ->addSaveAndCreate();

        $this->form[] = $buttons;

        return $this;
    }
}
