<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory\ContentList;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Form\Container\CheckboxGroup;
use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Element\Textarea;
use AbterPhp\Framework\Form\Extra\Help;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\Html\Component;
use AbterPhp\Framework\Html\INode;
use AbterPhp\Website\Domain\Entities\ContentListItem as Entity;

class Item
{
    /** @var int */
    protected $count = 1;

    /** @var string */
    protected $id;

    /** @var array */
    protected $hiddenAttribs = [Html5::ATTR_TYPE => Input::TYPE_HIDDEN];

    /**
     * @
     * @param Entity|null $entity
     * @param bool        $withLinks
     * @param bool        $withLabelLinks
     * @param bool        $withHtml
     * @param bool        $withImages
     * @param bool        $withClasses
     *
     * @return INode[]
     */
    public function create(
        ?Entity $entity,
        bool $withLinks,
        bool $withLabelLinks,
        bool $withHtml,
        bool $withImages,
        bool $withClasses
    ): array {
        $this->id = $entity ? "existing{$this->count}" : 'new';

        $components = [];

        if ($entity) {
            $components[] = $this->addId($entity);
        }

        $components[] = $this->addLabel($entity);
        if ($withLinks && $withLabelLinks) {
            $components[] = $this->addLabelHref($entity);
        }

        $components[] = $this->addContent($entity, $withHtml);
        if ($withLinks) {
            $components[] = $this->addContentHref($entity);
        }

        if ($withImages) {
            $components[] = $this->addImgSrc($entity);
            $components[] = $this->addImgAlt($entity);
            if ($withLinks) {
                $components[] = $this->addImgHref($entity);
            }
        }

        if ($withClasses) {
            $components[] = $this->addClasses($entity);
        }

        $components[] = $this->addIsDeleted();

        $this->count++;

        return $components;
    }

    /**
     * @param Entity $entity
     *
     * @return INode
     */
    protected function addId(Entity $entity): INode
    {
        return new Input("item_id_{$this->id}", "{$this->id}[id]", $entity->getId(), [], $this->hiddenAttribs);
    }

    /**
     * @param Entity|null $entity
     *
     * @return INode
     */
    protected function addLabel(?Entity $entity): INode
    {
        $label = $entity ? $entity->getLabel() : '';

        $input = new Input("item_label_{$this->id}", "{$this->id}[label]", $label);
        $label = new Label("item_label_{$this->id}", 'website:contentListItemLabel');
        $help  = new Help('website:contentListItemLabelHelp');

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity|null $entity
     *
     * @return INode
     */
    protected function addLabelHref(?Entity $entity): INode
    {
        $labelHref = $entity ? $entity->getLabelHref() : '';

        $input = new Input("item_label_href_{$this->id}", "{$this->id}[label_href]", $labelHref);
        $label = new Label("item_label_href_{$this->id}", 'website:contentListItemLabelHref');
        $help  = new Help('website:contentListItemLabelHrefHelp');

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity|null $entity
     * @param bool        $withHtml
     *
     * @return INode
     */
    protected function addContent(?Entity $entity, bool $withHtml): INode
    {
        $content = $entity ? $entity->getContent() : '';
        $attribs = [Html5::ATTR_ROWS => '7'];
        if ($withHtml) {
            $attribs = [Html5::ATTR_CLASS => 'wysiwyg', Html5::ATTR_ROWS => '15'];
        }

        $input = new Textarea("item_content_{$this->id}", "{$this->id}[content]", $content, [], $attribs);
        $label = new Label("item_content_{$this->id}", 'website:contentListItemContent');
        $help  = new Help('website:contentListItemContentHelp');

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity|null $entity
     *
     * @return INode
     */
    protected function addContentHref(?Entity $entity): INode
    {
        $contentHref = $entity ? $entity->getContentHref() : '';

        $input = new Input("item_content_href_{$this->id}", "{$this->id}[content_href]", $contentHref);
        $label = new Label("item_content_href_{$this->id}", 'website:contentListItemContentHref');
        $help  = new Help('website:contentListItemContentHrefHelp');

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity|null $entity
     *
     * @return INode
     */
    protected function addImgSrc(?Entity $entity): INode
    {
        $imgSrc = $entity ? $entity->getImgSrc() : '';

        $input = new Input("item_img_src_{$this->id}", "{$this->id}[img_src]", $imgSrc);
        $label = new Label("item_img_src_{$this->id}", 'website:contentListItemImgSrc');
        $help  = new Help('website:contentListItemImgSrcHelp');

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity|null $entity
     *
     * @return INode
     */
    protected function addImgAlt(?Entity $entity): INode
    {
        $imgAlt = $entity ? $entity->getImgAlt() : '';

        $input = new Input("item_img_alt_{$this->id}", "{$this->id}[img_alt]", $imgAlt);
        $label = new Label("item_img_alt_{$this->id}", 'website:contentListItemImgAlt');
        $help  = new Help('website:contentListItemImgAltHelp');

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity|null $entity
     *
     * @return INode
     */
    protected function addImgHref(?Entity $entity): INode
    {
        $imgHref = $entity ? $entity->getImgHref() : '';

        $input = new Input("item_img_href_{$this->id}", "{$this->id}[img_href]", $imgHref);
        $label = new Label("item_img_href_{$this->id}", 'website:contentListItemImgHref');
        $help  = new Help('website:contentListItemImgHrefHelp');

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity|null $entity
     *
     * @return INode
     */
    protected function addClasses(?Entity $entity): INode
    {
        $classes = $entity ? $entity->getClasses() : '';

        $input = new Input("item_classes_{$this->id}", "{$this->id}[classes]", $classes);
        $label = new Label("item_classes_{$this->id}", 'website:contentListItemClasses');
        $help  = new Help('website:contentListItemClassesHelp');

        return new FormGroup($input, $label, $help);
    }

    /**
     * @return INode
     */
    protected function addIsDeleted(): INode
    {
        $attributes = [Html5::ATTR_TYPE => Input::TYPE_CHECKBOX, Html5::ATTR_WRAP => ['item_is_deleted']];

        $input = new Input(
            "item_is_deleted_{$this->id}",
            "{$this->id}[is_deleted]",
            '1',
            [],
            $attributes
        );
        $label = new Label("item_is_deleted_{$this->id}", 'website:contentListItemIsDeleted');
        $help  = new Component('website:contentListItemIsDeletedHelp');

        return new CheckboxGroup($input, $label, $help, [], [Html5::ATTR_CLASS => 'is-deleted-container']);
    }
}
