<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory;

use AbterPhp\Admin\Form\Factory\Base;
use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Element\Textarea;
use AbterPhp\Framework\Form\Extra\Help;
use AbterPhp\Framework\Form\IForm;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Website\Domain\Entities\BlockLayout as Entity;
use Opulence\Orm\IEntity;
use Opulence\Sessions\ISession;

class BlockLayout extends Base
{
    /**
     * BlockLayout constructor.
     *
     * @param ISession    $session
     * @param ITranslator $translator
     */
    public function __construct(ISession $session, ITranslator $translator)
    {
        parent::__construct($session, $translator);
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
            ->addName($entity)
            ->addIdentifier($entity)
            ->addBody($entity)
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
    protected function addName(Entity $entity): BlockLayout
    {
        $input = new Input('name', 'name', $entity->getName());
        $label = new Label('name', 'website:blockLayoutName');

        $this->form[] = new FormGroup($input, $label, null, [], [Html5::ATTR_CLASS => FormGroup::CLASS_REQUIRED]);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addIdentifier(Entity $entity): BlockLayout
    {
        $input = new Input(
            'identifier',
            'identifier',
            $entity->getIdentifier(),
            [],
            [Html5::ATTR_CLASS => 'semi-auto']
        );
        $label = new Label('identifier', 'website:blockLayoutIdentifier');
        $help  = new Help('website:blockLayoutIdentifierHelp');

        $this->form[] = new FormGroup($input, $label, $help);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addBody(Entity $entity): BlockLayout
    {
        $input = new Textarea('body', 'body', $entity->getBody(), [], [Html5::ATTR_ROWS => '15']);
        $label = new Label('body', 'website:blockLayoutBody');

        $this->form[] = new FormGroup($input, $label);

        return $this;
    }
}
