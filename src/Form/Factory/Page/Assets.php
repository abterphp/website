<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory\Page;

use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Element\Textarea;
use AbterPhp\Framework\Form\Extra\Help;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\Html\INode;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Website\Domain\Entities\Page as Entity;

class Assets
{
    /** @var ITranslator */
    protected $translator;

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
     * @return INode[]
     */
    public function create(Entity $entity): array
    {
        $components = [];

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
    protected function addHeader(Entity $entity): INode
    {
        $header = $entity->getAssets() ? $entity->getAssets()->getHeader() : '';

        $input = new Textarea('header', 'header', $header);
        $label = new Label('header', 'website:pageHeader');
        $help  = new Help('website:pageHeaderHelp');

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
        $label = new Label('footer', 'website:pageFooter');
        $help  = new Help('website:pageHeaderHelp');

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
        $label = new Label('css-files', 'website:pageCssFiles');
        $help  = new Help('website:pageCssFilesHelp');

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
        $label = new Label('js-files', 'website:pageJsFiles');
        $help  = new Help('website:pageJsFilesHelp');

        return new FormGroup($input, $label, $help);
    }
}
