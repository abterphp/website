<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Container\Hideable;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Element\Textarea;
use AbterPhp\Framework\Form\Factory\Base;
use AbterPhp\Framework\Form\Factory\IFormFactory;
use AbterPhp\Framework\Form\IForm;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Website\Domain\Entities\PageLayout as Entity;
use AbterPhp\Website\Form\Factory\PageLayout\Assets as AssetsFactory;
use Opulence\Orm\IEntity;
use Opulence\Sessions\ISession;

class PageLayout extends Base
{
    /** @var AssetsFactory */
    protected $assetsFactory;

    /**
     * PageLayout constructor.
     *
     * @param ISession      $session
     * @param ITranslator   $translator
     * @param AssetsFactory $assetsFactory
     */
    public function __construct(ISession $session, ITranslator $translator, AssetsFactory $assetsFactory)
    {
        parent::__construct($session, $translator);

        $this->assetsFactory = $assetsFactory;
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
        if (!($entity instanceof Entity)) {
            throw new \InvalidArgumentException(IFormFactory::ERR_MSG_ENTITY_MISSING);
        }

        $this->createForm($action, $method)
            ->addDefaultElements()
            ->addIdentifier($entity)
            ->addBody($entity)
            ->addAssets($entity)
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
    protected function addIdentifier(Entity $entity): PageLayout
    {
        $input = new Input('identifier', 'identifier', $entity->getIdentifier());
        $label = new Label('identifier', 'website:pageLayoutIdentifier');

        $this->form[] = new FormGroup($input, $label, null);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addBody(Entity $entity): PageLayout
    {
        $input = new Textarea('body', 'body', $entity->getBody(), [], [Html5::ATTR_ROWS => '15']);
        $label = new Label('body', 'website:pageLayoutBody');

        $this->form[] = new FormGroup($input, $label);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addAssets(Entity $entity): PageLayout
    {
        $hideable = new Hideable($this->translator->translate('website:pageLayoutAssetsBtn'));
        foreach ($this->assetsFactory->create($entity) as $component) {
            $hideable[] = $component;
        }

        $this->form[] = $hideable;

        return $this;
    }
}
