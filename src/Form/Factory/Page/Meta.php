<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory\Page;

use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Element\Textarea;
use AbterPhp\Framework\Form\Extra\Help;
use AbterPhp\Framework\Form\Label\Countable;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\Html\INode;
use AbterPhp\Website\Domain\Entities\Page as Entity;

class Meta
{
    /**
     * @return INode[]
     */
    public function create(Entity $entity): array
    {
        $components = [];

        $components[] = $this->addOGTitle($entity);
        $components[] = $this->addOGImage($entity);
        $components[] = $this->addOGDescription($entity);
        $components[] = $this->addAuthor($entity);
        $components[] = $this->addCopyright($entity);
        $components[] = $this->addKeywords($entity);
        $components[] = $this->addRobots($entity);

        return $components;
    }

    /**
     * @param Entity $entity
     *
     * @return INode
     */
    protected function addOGTitle(Entity $entity): INode
    {
        $input = new Input('og-title', 'og-title', $entity->getMeta()->getOGTitle());
        $label = new Label('og-title', 'website:pageOGTitle');
        $help  = new Help('website:pageOGTitleHelp');

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity $entity
     *
     * @return INode
     */
    protected function addOGImage(Entity $entity): INode
    {
        $input = new Input('og-image', 'og-image', $entity->getMeta()->getOGImage());
        $label = new Label('og-image', 'website:pageOGImage');
        $help  = new Help('website:pageOGImageHelp');

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity $entity
     *
     * @return INode
     */
    protected function addOGDescription(Entity $entity): INode
    {
        $input = new Textarea('og-description', 'og-description', $entity->getMeta()->getOGDescription());
        $label = new Countable(
            'og-description',
            'website:pageOGDescription',
            Countable::DEFAULT_SIZE
        );
        $help  = new Help('website:pageOGDescriptionHelp');

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity $entity
     *
     * @return INode
     */
    protected function addAuthor(Entity $entity): INode
    {
        $input = new Input('author', 'author', $entity->getMeta()->getAuthor());
        $label = new Label('author', 'website:pageAuthor');
        $help  = new Help('website:pageAuthor');

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity $entity
     *
     * @return INode
     */
    protected function addCopyright(Entity $entity): INode
    {
        $input = new Input('copyright', 'copyright', $entity->getMeta()->getAuthor());
        $label = new Label('copyright', 'website:pageCopyright');
        $help  = new Help('website:pageCopyrightHelp');

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity $entity
     *
     * @return INode
     */
    protected function addKeywords(Entity $entity): INode
    {
        $input = new Input('keywords', 'keywords', $entity->getMeta()->getKeywords());
        $label = new Label('keywords', 'website:pageKeywords');
        $help  = new Help('website:pageKeywordsHelp');

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity $entity
     *
     * @return INode
     */
    protected function addRobots(Entity $entity): INode
    {
        $input = new Input('robots', 'robots', $entity->getMeta()->getRobots());
        $label = new Label('robots', 'website:pageRobots');
        $help  = new Help('website:pageRobotsHelp');

        return new FormGroup($input, $label, $help);
    }
}
