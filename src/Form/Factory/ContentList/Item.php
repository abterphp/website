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
    protected static $existingCount = 1;

    /** @var bool */
    protected $protected;

    /** @var bool */
    protected $withImage;

    /** @var bool */
    protected $withLinks;

    /** @var string */
    protected $id;

    /** @var array */
    protected $hiddenAttribs = [Html5::ATTR_TYPE => Input::TYPE_HIDDEN];

    /**
     * Item constructor.
     *
     * @param bool $protected
     * @param bool $withImage
     * @param bool $withLinks
     */
    public function __construct(bool $protected, bool $withImage, bool $withLinks)
    {
        $this->protected = $protected;
        $this->withImage = $withImage;
        $this->withLinks = $withLinks;
    }

    /**
     * @param Entity|null $entity
     *
     * @return INode[]
     */
    public function create(?Entity $entity = null): array
    {
        $count    = static::$existingCount++;
        $this->id = $entity ? "existing{$count}" : 'new';

        $components = [];

        if ($entity) {
            $components[] = $this->addId($entity);
        }

        $components[] = $this->addName($entity);
        $components[] = $this->addNameHref($entity);
        $components[] = $this->addBody($entity);
        $components[] = $this->addBodyHref($entity);
        $components[] = $this->addImgSrc($entity);
        $components[] = $this->addImgAlt($entity);
        $components[] = $this->addImgHref($entity);
        $components[] = $this->addIsDeleted();

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
    protected function addName(?Entity $entity): INode
    {
        $name = $entity ? $entity->getName() : '';

        $input = new Input("item_name_{$this->id}", "{$this->id}[name]", $name);
        $label = new Label("item_name_{$this->id}", 'website:contentListItemName');
        $help  = new Help('website:contentListItemNameHelp');

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity|null $entity
     *
     * @return INode
     */
    protected function addNameHref(?Entity $entity): INode
    {
        if (!$this->withLinks) {
            return new Input(
                "item_name_href_{$this->id}",
                "item_name_href[{$this->id}]",
                '',
                [],
                $this->hiddenAttribs
            );
        }

        $nameHref = $entity ? $entity->getNameHref() : '';

        $input = new Input("item_name_href_{$this->id}", "{$this->id}[name_href]", $nameHref);
        $label = new Label("item_name_href_{$this->id}", 'website:contentListItemNameHref');
        $help  = new Help('website:contentListItemNameHrefHelp');

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity|null $entity
     *
     * @return INode
     */
    protected function addBody(?Entity $entity): INode
    {
        $body = $entity ? $entity->getBody() : '';

        $input = new Textarea("item_body_{$this->id}", "{$this->id}[body]", $body);
        $label = new Label("item_body_{$this->id}", 'website:contentListItemBody');
        $help  = new Help('website:contentListItemBodyHelp');

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity|null $entity
     *
     * @return INode
     */
    protected function addBodyHref(?Entity $entity): INode
    {
        if (!$this->withLinks) {
            return new Input("item_body_href_{$this->id}", "{$this->id}[body_href]", '', [], $this->hiddenAttribs);
        }

        $bodyHref = $entity ? $entity->getBodyHref() : '';

        $input = new Input("item_body_href_{$this->id}", "{$this->id}[body_href]", $bodyHref);
        $label = new Label("item_body_href_{$this->id}", 'website:contentListItemBodyHref');
        $help  = new Help('website:contentListItemBodyHrefHelp');

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity|null $entity
     *
     * @return INode
     */
    protected function addImgSrc(?Entity $entity): INode
    {
        if (!$this->withImage) {
            return new Input("item_img_src_{$this->id}", "{$this->id}[img_src]", '', [], $this->hiddenAttribs);
        }

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
        if (!$this->withImage) {
            return new Input("item_img_alt_{$this->id}", "{$this->id}[img_alt]", '', [], $this->hiddenAttribs);
        }

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
        if (!$this->withImage || !$this->withLinks) {
            return new Input("item_img_href_{$this->id}", "{$this->id}[img_href]", '', [], $this->hiddenAttribs);
        }

        $imgHref = $entity ? $entity->getImgHref() : '';

        $input = new Input("item_img_href_{$this->id}", "{$this->id}[img_href]", $imgHref);
        $label = new Label("item_img_href_{$this->id}", 'website:contentListItemImgHref');
        $help  = new Help('website:contentListItemImgHrefHelp');

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
