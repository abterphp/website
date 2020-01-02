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
     * @param Entity|null $entity
     * @param bool        $withLinks
     * @param bool        $withImage
     * @param bool        $withHtml
     *
     * @return INode[]
     */
    public function create(?Entity $entity, bool $withLinks, bool $withImage, bool $withBody, bool $withHtml): array
    {
        $this->count++;

        $this->id = $entity ? "existing{$this->count}" : 'new';

        $components = [];

        if ($entity) {
            $components[] = $this->addId($entity);
        }

        $components[] = $this->addName($entity);
        if ($withLinks) {
            $components[] = $this->addNameHref($entity);
        }
        if ($withBody) {
            $components[] = $this->addBody($entity, $withHtml);
            if ($withLinks) {
                $components[] = $this->addBodyHref($entity);
            }
        }
        if ($withImage) {
            $components[] = $this->addImgSrc($entity);
            $components[] = $this->addImgAlt($entity);
            if ($withLinks) {
                $components[] = $this->addImgHref($entity);
            }
        }
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
        $nameHref = $entity ? $entity->getNameHref() : '';

        $input = new Input("item_name_href_{$this->id}", "{$this->id}[name_href]", $nameHref);
        $label = new Label("item_name_href_{$this->id}", 'website:contentListItemNameHref');
        $help  = new Help('website:contentListItemNameHrefHelp');

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity|null $entity
     * @param bool        $withHtml
     *
     * @return INode
     */
    protected function addBody(?Entity $entity, bool $withHtml): INode
    {
        $body    = $entity ? $entity->getBody() : '';
        $attribs = [];
        if ($withHtml) {
            $attribs = [Html5::ATTR_CLASS => 'wysiwyg', Html5::ATTR_ROWS => '15'];
        }

        $input = new Textarea("item_body_{$this->id}", "{$this->id}[body]", $body, [], $attribs);
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
