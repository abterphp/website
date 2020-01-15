<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory\PageLayout;

use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Element\Textarea;
use AbterPhp\Framework\Form\Extra\Help;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\Html\INode;
use AbterPhp\Website\Domain\Entities\PageLayout as Entity;

class Assets
{
    /**
     * @return INode[]
     */
    public function create(Entity $entity): array
    {
        $components = [];

        $components[] = $this->addClasses($entity);
        $components[] = $this->addHeader($entity);
        $components[] = $this->addFooter($entity);
        $components[] = $this->addCssFiles($entity);
        $components[] = $this->addJsFiles($entity);

        return $components;
    }

    /**
     * @param Entity $entity
     *
     * @return INode
     */
    protected function addClasses(Entity $entity): INode
    {
        $input = new Textarea('classes', 'classes', $entity->getClasses());
        $label = new Label('classes', 'website:pageClasses');
        $help  = new Help('website:pageClassesHelp');

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity $entity
     *
     * @return INode
     */
    protected function addHeader(Entity $entity): INode
    {
        $header = $entity->getAssets() ? $entity->getAssets()->getHeader() : '';

        $input = new Textarea('header', 'header', $header);
        $label = new Label('header', 'website:pageLayoutHeader');
        $help  = new Help('website:pageLayoutHeaderHelp');

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity $entity
     *
     * @return INode
     */
    protected function addFooter(Entity $entity): INode
    {
        $footer = $entity->getAssets() ? $entity->getAssets()->getFooter() : '';

        $input = new Textarea('footer', 'footer', $footer);
        $label = new Label('footer', 'website:pageLayoutFooter');
        $help  = new Help('website:pageLayoutHeaderHelp');

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity $entity
     *
     * @return INode
     */
    protected function addCssFiles(Entity $entity): INode
    {
        $cssFiles = $entity->getAssets() ? $entity->getAssets()->getCssFiles() : [];

        $input = new Textarea('css-files', 'css-files', implode("\r\n", $cssFiles));
        $label = new Label('css-files', 'website:pageLayoutCssFiles');
        $help  = new Help('website:pageLayoutCssFilesHelp');

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity $entity
     *
     * @return INode
     */
    protected function addJsFiles(Entity $entity): INode
    {
        $jsFiles = $entity->getAssets() ? $entity->getAssets()->getJsFiles() : [];

        $input = new Textarea('js-files', 'js-files', implode("\r\n", $jsFiles));
        $label = new Label('js-files', 'website:pageLayoutJsFiles');
        $help  = new Help('website:pageLayoutJsFilesHelp');

        return new FormGroup($input, $label, $help);
    }
}
