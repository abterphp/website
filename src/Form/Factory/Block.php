<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory;

use AbterPhp\Admin\Form\Factory\Base;
use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Constant\Session;
use AbterPhp\Framework\Form\Component\Option;
use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Element\Select;
use AbterPhp\Framework\Form\Element\Textarea;
use AbterPhp\Framework\Form\IForm;
use AbterPhp\Framework\Form\Label\Countable;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Website\Constant\Authorization;
use AbterPhp\Website\Domain\Entities\Block as Entity;
use AbterPhp\Website\Domain\Entities\BlockLayout;
use AbterPhp\Website\Orm\BlockLayoutRepo;
use Casbin\Enforcer;
use Opulence\Orm\IEntity;
use Opulence\Sessions\ISession;

class Block extends Base
{
    /** @var BlockLayoutRepo */
    protected $layoutRepo;

    /** @var Enforcer */
    protected $enforcer;

    /**
     * Block constructor.
     *
     * @param ISession        $session
     * @param ITranslator     $translator
     * @param BlockLayoutRepo $layoutRepo
     * @param Enforcer        $enforcer
     */
    public function __construct(
        ISession $session,
        ITranslator $translator,
        BlockLayoutRepo $layoutRepo,
        Enforcer $enforcer
    ) {
        parent::__construct($session, $translator);

        $this->layoutRepo = $layoutRepo;
        $this->enforcer   = $enforcer;
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

        $username        = $this->session->get(Session::USERNAME);
        $advancedAllowed = $this->enforcer->enforce(
            $username,
            Authorization::RESOURCE_PAGES,
            Authorization::ROLE_PAGES_ADVANCED_WRITE
        );

        $this->createForm($action, $method)
            ->addDefaultElements()
            ->addIdentifier($entity)
            ->addTitle($entity)
            ->addBody($entity)
            ->addLayoutId($entity)
            ->addLayout($entity, $advancedAllowed)
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
    protected function addIdentifier(Entity $entity): Block
    {
        $input = new Input('identifier', 'identifier', $entity->getIdentifier());
        $label = new Label('title', 'website:blockIdentifier');

        $this->form[] = new FormGroup($input, $label);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addTitle(Entity $entity): Block
    {
        $input = new Input('title', 'title', $entity->getTitle());
        $label = new Label('title', 'website:blockTitle');

        $this->form[] = new FormGroup($input, $label);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addBody(Entity $entity): Block
    {
        $attribs = [Html5::ATTR_CLASS => Textarea::CLASS_WYSIWYG, Html5::ATTR_ROWS => '15'];
        $input   = new Textarea('body', 'body', $entity->getBody(), [], $attribs);
        $label   = new Label('body', 'website:blockBody');

        $this->form[] = new FormGroup($input, $label);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addLayoutId(Entity $entity): Block
    {
        $allLayouts = $this->getAllLayouts();
        $layoutId   = $entity->getLayoutId();

        $options = $this->createLayoutIdOptions($allLayouts, $layoutId);

        $this->form[] = new FormGroup(
            $this->createLayoutIdSelect($options),
            $this->createLayoutIdLabel()
        );

        return $this;
    }

    /**
     * @return BlockLayout[]
     */
    protected function getAllLayouts(): array
    {
        return $this->layoutRepo->getAll();
    }

    /**
     * @param BlockLayout[] $allLayouts
     * @param string|null   $layoutId
     *
     * @return Option[]
     */
    protected function createLayoutIdOptions(array $allLayouts, ?string $layoutId): array
    {
        $options   = [];
        $options[] = new Option('', 'framework:none', false);
        foreach ($allLayouts as $layout) {
            $isSelected = $layout->getId() === $layoutId;
            $options[]  = new Option($layout->getId(), $layout->getIdentifier(), $isSelected);
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
        return new Label('layout_id', 'website:blockLayoutIdLabel');
    }

    /**
     * @param Entity $entity
     * @param bool   $advancedAllowed
     *
     * @return $this
     */
    protected function addLayout(Entity $entity, bool $advancedAllowed): Block
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
    protected function addLayoutHidden(Entity $entity): Block
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
    protected function addLayoutTextarea(Entity $entity): Block
    {
        $input   = new Textarea('layout', 'layout', $entity->getLayout(), [], [Html5::ATTR_ROWS => '15']);
        $label   = new Countable('description', 'website:blockLayoutLabel', Countable::DEFAULT_SIZE);
        $attribs = [Html5::ATTR_ID => 'layout-div', Html5::ATTR_CLASS => FormGroup::CLASS_COUNTABLE];

        $this->form[] = new FormGroup($input, $label, null, [], $attribs);

        return $this;
    }
}
