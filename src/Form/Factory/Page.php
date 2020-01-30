<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory;

use AbterPhp\Admin\Form\Factory\Base;
use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Constant\Session;
use AbterPhp\Framework\Form\Component\Option;
use AbterPhp\Framework\Form\Container\CheckboxGroup;
use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Container\Hideable;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Element\Select;
use AbterPhp\Framework\Form\Element\Textarea;
use AbterPhp\Framework\Form\Extra\DefaultButtons;
use AbterPhp\Framework\Form\Extra\Help;
use AbterPhp\Framework\Form\IForm;
use AbterPhp\Framework\Form\Label\Countable;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\Html\Component;
use AbterPhp\Framework\Html\Component\Button;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Website\Constant\Authorization;
use AbterPhp\Website\Domain\Entities\Page as Entity;
use AbterPhp\Website\Domain\Entities\PageCategory;
use AbterPhp\Website\Domain\Entities\PageLayout;
use AbterPhp\Website\Form\Factory\Page\Assets as AssetsFactory;
use AbterPhp\Website\Form\Factory\Page\Meta as MetaFactory;
use AbterPhp\Website\Orm\PageCategoryRepo;
use AbterPhp\Website\Orm\PageLayoutRepo;
use Casbin\Enforcer;
use Opulence\Orm\IEntity;
use Opulence\Sessions\ISession;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Page extends Base
{
    const BTN_CONTENT_PUBLISH_AND_EDIT       = 'website:publishAndEdit';
    const BTN_CONTENT_SAVE_AS_DRAFT_AND_EDIT = 'website:saveAsDraftAndEdit';

    const BTN_ID_DRAFT   = 'draft-btn';
    const BTN_ID_PUBLISH = 'publish-btn';

    /** @var PageCategoryRepo */
    protected $categoryRepo;

    /** @var PageLayoutRepo */
    protected $layoutRepo;

    /** @var MetaFactory */
    protected $metaFactory;

    /** @var AssetsFactory */
    protected $assetsFactory;

    /** @var Enforcer */
    protected $enforcer;

    /**
     * Page constructor.
     *
     * @param ISession         $session
     * @param ITranslator      $translator
     * @param PageCategoryRepo $categoryRepo
     * @param PageLayoutRepo   $layoutRepo
     * @param MetaFactory      $metaFactory
     * @param AssetsFactory    $assetsFactory
     * @param Enforcer         $enforcer
     */
    public function __construct(
        ISession $session,
        ITranslator $translator,
        PageCategoryRepo $categoryRepo,
        PageLayoutRepo $layoutRepo,
        MetaFactory $metaFactory,
        AssetsFactory $assetsFactory,
        Enforcer $enforcer
    ) {
        parent::__construct($session, $translator);

        $this->categoryRepo  = $categoryRepo;
        $this->layoutRepo    = $layoutRepo;
        $this->metaFactory   = $metaFactory;
        $this->assetsFactory = $assetsFactory;
        $this->enforcer      = $enforcer;
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

        $username        = $this->session->get(Session::USERNAME);
        $advancedAllowed = $this->enforcer->enforce(
            $username,
            Authorization::RESOURCE_PAGES,
            Authorization::ROLE_ADVANCED_WRITE
        );

        $this->createForm($action, $method)
            ->addDefaultElements()
            ->addTitle($entity)
            ->addIdentifier($entity)
            ->addDescription($entity)
            ->addMeta($entity)
            ->addLead($entity)
            ->addBody($entity)
            ->addCategoryId($entity)
            ->addLayoutId($entity, $advancedAllowed)
            ->addLayout($entity, $advancedAllowed)
            ->addAssets($entity, $advancedAllowed)
            ->addIsDraft($entity)
            ->addCustomButtons($showUrl);

        $form = $this->form;

        $this->form = null;

        return $form;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addTitle(Entity $entity): Page
    {
        $input = new Input('title', 'title', $entity->getTitle());
        $label = new Label('title', 'website:pageTitle');

        $this->form[] = new FormGroup($input, $label, null, [], [Html5::ATTR_CLASS => FormGroup::CLASS_REQUIRED]);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addIdentifier(Entity $entity): Page
    {
        $input = new Input(
            'identifier',
            'identifier',
            $entity->getIdentifier(),
            [],
            [Html5::ATTR_CLASS => 'semi-auto']
        );
        $label = new Label('identifier', 'website:pageIdentifier');
        $help  = new Help('website:pageIdentifierHelp');

        $this->form[] = new FormGroup($input, $label, $help);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addDescription(Entity $entity): Page
    {
        $input = new Textarea('description', 'description', $entity->getMeta()->getDescription());
        $label = new Countable('description', 'website:pageDescription', Countable::DEFAULT_SIZE);
        $help  = new Help('website:pageDescriptionHelp');

        $this->form[] = new FormGroup(
            $input,
            $label,
            $help,
            [],
            [Html5::ATTR_CLASS => FormGroup::CLASS_COUNTABLE]
        );

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addMeta(Entity $entity): Page
    {
        $hideable = new Hideable($this->translator->translate('website:pageMetaBtn'));
        foreach ($this->metaFactory->create($entity) as $component) {
            $hideable[] = $component;
        }

        $this->form[] = $hideable;

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addLead(Entity $entity): Page
    {
        $attribs = [Html5::ATTR_ROWS => '10'];
        $input   = new Textarea('lead', 'lead', $entity->getLead(), [], $attribs);
        $label   = new Label('lead', 'website:pageLead');
        $help    = new Help('website:pageLeadHelp');

        $this->form[] = new FormGroup($input, $label, $help);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addBody(Entity $entity): Page
    {
        $attribs = [Html5::ATTR_CLASS => 'wysiwyg', Html5::ATTR_ROWS => '15'];
        $input   = new Textarea('body', 'body', $entity->getBody(), [], $attribs);
        $label   = new Label('body', 'website:pageBody');

        $this->form[] = new FormGroup($input, $label);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addCategoryId(Entity $entity): Page
    {
        $allCategories = $this->getAllCategories();
        $categoryId    = $entity->getCategory() ? $entity->getCategory()->getId() : null;

        $options = $this->createCategoryIdOptions($allCategories, $categoryId);

        $this->form[] = new FormGroup(
            $this->createCategoryIdSelect($options),
            $this->createCategoryIdLabel()
        );

        return $this;
    }

    /**
     * @param Option[] $options
     *
     * @return Select
     */
    protected function createCategoryIdSelect(array $options): Select
    {
        $select = new Select('category_id', 'category_id');

        foreach ($options as $option) {
            $select[] = $option;
        }

        return $select;
    }

    /**
     * @return Label
     */
    protected function createCategoryIdLabel(): Label
    {
        return new Label('category_id', 'website:pageCategoryIdLabel');
    }

    /**
     * @return PageCategory[]
     */
    protected function getAllCategories(): array
    {
        return $this->categoryRepo->getAll();
    }

    /**
     * @param PageCategory[] $allCategories
     * @param string|null    $categoryId
     *
     * @return Option[]
     */
    protected function createCategoryIdOptions(array $allCategories, ?string $categoryId): array
    {
        $options   = [];
        $options[] = new Option('', 'framework:none', false);
        foreach ($allCategories as $category) {
            $isSelected = $category->getId() === $categoryId;
            $options[]  = new Option($category->getId(), $category->getName(), $isSelected);
        }

        return $options;
    }

    /**
     * @param Entity $entity
     * @param bool   $advancedAllowed
     *
     * @return $this
     */
    protected function addLayoutId(Entity $entity, bool $advancedAllowed): Page
    {
        if ($advancedAllowed && $entity->getId() && !$entity->getLayoutId()) {
            return $this;
        }

        $allLayouts = $this->getAllLayouts();
        $layoutId   = $entity->getLayoutId();

        $options = $this->createLayoutIdOptions($allLayouts, $layoutId, $advancedAllowed);

        $this->form[] = new FormGroup(
            $this->createLayoutIdSelect($options),
            $this->createLayoutIdLabel()
        );

        return $this;
    }

    /**
     * @return PageLayout[]
     */
    protected function getAllLayouts(): array
    {
        return $this->layoutRepo->getAll();
    }

    /**
     * @param PageLayout[] $allLayouts
     * @param string|null  $layoutId
     * @param bool         $advancedAllowed
     *
     * @return Option[]
     */
    protected function createLayoutIdOptions(array $allLayouts, ?string $layoutId, bool $advancedAllowed): array
    {
        $options = [];
        if ($advancedAllowed) {
            $options[] = new Option('', 'framework:none', false);
        }
        foreach ($allLayouts as $layout) {
            $isSelected = $layout->getId() === $layoutId;
            $options[]  = new Option($layout->getId(), $layout->getName(), $isSelected);
        }

        return $options;
    }

    /**
     * @param Option[] $options
     *
     * @return Select
     */
    protected function createLayoutIdSelect(array $options): Select
    {
        $select = new Select('layout_id', 'layout_id');

        foreach ($options as $option) {
            $select[] = $option;
        }

        return $select;
    }

    /**
     * @return Label
     */
    protected function createLayoutIdLabel(): Label
    {
        return new Label('layout_id', 'website:pageLayoutIdLabel');
    }

    /**
     * @param Entity $entity
     * @param bool   $advancedAllowed
     *
     * @return Page
     */
    protected function addLayout(Entity $entity, bool $advancedAllowed): Page
    {
        if (!$advancedAllowed) {
            return $this->addLayoutHidden($entity);
        }

        return $this->addLayoutTextarea($entity);
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addLayoutHidden(Entity $entity): Page
    {
        $attribs = [Html5::ATTR_TYPE => Input::TYPE_HIDDEN];

        $this->form[] = new Input('layout', 'layout', $entity->getLayout(), [], $attribs);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addLayoutTextarea(Entity $entity): Page
    {
        $input = new Textarea('layout', 'layout', $entity->getLayout(), [], [Html5::ATTR_ROWS => '15']);
        $label = new Label('layout', 'website:pageLayoutLabel');

        $this->form[] = new FormGroup($input, $label, null, [], [Html5::ATTR_ID => 'layout-div']);

        return $this;
    }

    /**
     * @param Entity $entity
     * @param bool   $advancedAllowed
     *
     * @return $this
     */
    protected function addAssets(Entity $entity, bool $advancedAllowed): Page
    {
        if (!$advancedAllowed) {
            return $this;
        }

        $nodes = $this->assetsFactory->create($entity);
        if (empty($nodes)) {
            return $this;
        }

        $container = new Hideable($this->translator->translate('website:pageAssetsBtn'));
        foreach ($nodes as $node) {
            $container[] = $node;
        }

        $this->form[] = $container;

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addIsDraft(Entity $entity): Page
    {
        $attributes = [Html5::ATTR_TYPE => Input::TYPE_CHECKBOX];
        if ($entity->isDraft()) {
            $attributes[Html5::ATTR_CHECKED] = null;
        }
        $input = new Input(
            'is_draft',
            'is_draft',
            '1',
            [],
            $attributes
        );
        $label = new Label('is_draft', 'website:pageIsDraft');
        $help  = new Component('website:pageIsDraft');

        $this->form[] = new CheckboxGroup($input, $label, $help, [], [Html5::ATTR_ID => 'is-draft-container']);

        return $this;
    }

    /**
     * @param string $showUrl
     *
     * @return Base
     */
    protected function addCustomButtons(string $showUrl): Base
    {
        $buttons = new DefaultButtons();

        $this->addPublishAndEdit($buttons);

        $buttons
            ->addSaveAndBack()
            ->addBackToGrid($showUrl);

        $this->addSaveAsDraftAndBack($buttons);

        $buttons->addSaveAndEdit()->addSaveAndCreate();

        $this->form[] = $buttons;

        return $this;
    }


    /**
     * @param DefaultButtons $buttons
     *
     * @return DefaultButtons
     */
    public function addPublishAndEdit(DefaultButtons $buttons): DefaultButtons
    {
        $attributes = [
            Html5::ATTR_NAME  => [DefaultButtons::BTN_NAME_NEXT],
            Html5::ATTR_TYPE  => [Button::TYPE_SUBMIT],
            Html5::ATTR_VALUE => [DefaultButtons::BTN_VALUE_NEXT_EDIT],
            Html5::ATTR_ID    => [static::BTN_ID_PUBLISH],
        ];

        $buttons[] = new Button(
            static::BTN_CONTENT_PUBLISH_AND_EDIT,
            [Button::INTENT_SUCCESS, Button::INTENT_FORM, Button::INTENT_LARGE, Button::INTENT_HIDDEN],
            $attributes
        );

        return $buttons;
    }

    /**
     * @param DefaultButtons $buttons
     *
     * @return DefaultButtons
     */
    public function addSaveAsDraftAndBack(DefaultButtons $buttons): DefaultButtons
    {
        $attributes = [
            Html5::ATTR_NAME  => [DefaultButtons::BTN_NAME_NEXT],
            Html5::ATTR_TYPE  => [Button::TYPE_SUBMIT],
            Html5::ATTR_VALUE => [DefaultButtons::BTN_VALUE_NEXT_EDIT],
            Html5::ATTR_ID    => [static::BTN_ID_DRAFT],
        ];

        $buttons[] = new Button(
            static::BTN_CONTENT_SAVE_AS_DRAFT_AND_EDIT,
            [Button::INTENT_WARNING, Button::INTENT_FORM, Button::INTENT_HIDDEN],
            $attributes
        );

        return $buttons;
    }
}
