<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory\ContentList;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Form\Container\CheckboxGroup;
use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Extra\Help;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\Html\INode;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Website\Domain\Entities\ContentList as Entity;

class Advanced
{
    /** @var ITranslator */
    protected $translator;

    /** @var array */
    protected $hiddenAttribs = [Html5::ATTR_TYPE => Input::TYPE_HIDDEN];

    /**
     * Hideable constructor.
     *
     * @param ITranslator $translator
     */
    public function __construct(ITranslator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param Entity $entity
     *
     * @return INode[]
     */
    public function create(Entity $entity): array
    {
        $components = [];

        $components[] = $this->addClasses($entity);
        $components[] = $this->addProtected($entity);
        $components[] = $this->addWithLinks($entity);
        $components[] = $this->addWithImage($entity);
        $components[] = $this->addWithBody($entity);
        $components[] = $this->addWithHtml($entity);

        return $components;
    }

    /**
     * @param Entity $entity
     *
     * @return INode
     */
    protected function addClasses(Entity $entity): INode
    {
        $input = new Input('classes', 'classes', $entity->getClasses());
        $label = new Label('classes', 'website:contentListClasses');
        $help  = new Help('website:contentListClassesHelp');

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity $entity
     *
     * @return INode
     */
    protected function addProtected(Entity $entity): INode
    {
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

        return new CheckboxGroup($input, $label, $help, [], [Html5::ATTR_ID => 'protected-container']);
    }

    /**
     * @param Entity $entity
     *
     * @return INode
     */
    protected function addWithImage(Entity $entity): INode
    {
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

        return new CheckboxGroup($input, $label, $help, [], [Html5::ATTR_ID => 'withImage-container']);
    }

    /**
     * @param Entity $entity
     *
     * @return INode
     */
    protected function addWithLinks(Entity $entity): INode
    {
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

        return new CheckboxGroup($input, $label, $help, [], [Html5::ATTR_ID => 'withLinks-container']);
    }

    /**
     * @param Entity $entity
     *
     * @return INode
     */
    protected function addWithBody(Entity $entity): INode
    {
        $attributes = [Html5::ATTR_TYPE => Input::TYPE_CHECKBOX];
        if ($entity->isWithBody()) {
            $attributes[Html5::ATTR_CHECKED] = null;
        }
        $input = new Input(
            'with_body',
            'with_body',
            '1',
            [],
            $attributes
        );
        $label = new Label('with_body', 'website:contentListWithBody');
        $help  = new Help('website:contentListWithBodyHelp');

        return new CheckboxGroup($input, $label, $help, [], [Html5::ATTR_ID => 'withBody-container']);
    }

    /**
     * @param Entity $entity
     *
     * @return INode
     */
    protected function addWithHtml(Entity $entity): INode
    {
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

        return new CheckboxGroup($input, $label, $help, [], [Html5::ATTR_ID => 'withHtml-container']);
    }
}
