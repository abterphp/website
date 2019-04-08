<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Admin\Form;

use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Http\Controllers\Admin\FormAbstract;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Website\Domain\Entities\Block as Entity;
use AbterPhp\Website\Form\Factory\Block as FormFactory;
use AbterPhp\Website\Orm\BlockRepo as Repo;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Orm\OrmException;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;

class Block extends FormAbstract
{
    const ENTITY_PLURAL   = 'blocks';
    const ENTITY_SINGULAR = 'block';

    const ENTITY_TITLE_SINGULAR = 'website:block';
    const ENTITY_TITLE_PLURAL   = 'website:blocks';

    /** @var AssetManager */
    protected $assets;

    /** @var string */
    protected $resource = 'blocks';

    /**
     * Block constructor.
     *
     * @param FlashService     $flashService
     * @param ITranslator      $translator
     * @param UrlGenerator     $urlGenerator
     * @param Repo             $repo
     * @param ISession         $session
     * @param FormFactory      $formFactory
     * @param AssetManager     $assetManager
     * @param IEventDispatcher $eventDispatcher
     */
    public function __construct(
        FlashService $flashService,
        ITranslator $translator,
        UrlGenerator $urlGenerator,
        Repo $repo,
        ISession $session,
        FormFactory $formFactory,
        AssetManager $assetManager,
        IEventDispatcher $eventDispatcher
    ) {
        parent::__construct($flashService, $translator, $urlGenerator, $repo, $session, $formFactory, $eventDispatcher);

        $this->formFactory = $formFactory;
        $this->assets      = $assetManager;
    }

    /**
     * @param string $entityId
     *
     * @return Entity
     */
    protected function createEntity(string $entityId): IStringerEntity
    {
        return new Entity($entityId, '', '', '', '', null);
    }

    /**
     * @param Entity|null $entity
     *
     * @throws OrmException
     */
    protected function addCustomAssets(?IStringerEntity $entity = null)
    {
        parent::addCustomAssets($entity);

        if (!($entity instanceof Entity)) {
            return;
        }

        $styles = $this->getResourceName(static::RESOURCE_DEFAULT);
        $this->assets->addCss($styles, '/admin-assets/vendor/trumbowyg/ui/trumbowyg.css');

        $footer = $this->getResourceName(static::RESOURCE_FOOTER);
        $this->assets->addJs($footer, '/admin-assets/vendor/trumbowyg/trumbowyg.js');
        $this->assets->addJs($footer, '/admin-assets/vendor/trumbowyg/langs/hu.js');
        $this->assets->addJs($footer, '/admin-assets/js/editor.js');
        $this->assets->addJs($footer, '/admin-assets/js/layout-or-id.js');
    }
}
