<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Admin\Form;

use AbterPhp\Admin\Http\Controllers\Admin\FormAbstract;
use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Website\Domain\Entities\ContentList as Entity;
use AbterPhp\Website\Form\Factory\ContentList as FormFactory;
use AbterPhp\Website\Orm\ContentListRepo as Repo;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Psr\Log\LoggerInterface;

class ContentList extends FormAbstract
{
    const ENTITY_SINGULAR = 'contentList';
    const ENTITY_PLURAL   = 'contentLists';

    const ENTITY_TITLE_SINGULAR = 'website:contentList';
    const ENTITY_TITLE_PLURAL   = 'website:contentLists';

    const ROUTING_PATH = 'lists';

    /** @var AssetManager */
    protected $assetManager;

    /** @var string */
    protected $resource = 'lists';

    /**
     * Block constructor.
     *
     * @param FlashService     $flashService
     * @param ITranslator      $translator
     * @param UrlGenerator     $urlGenerator
     * @param LoggerInterface  $logger
     * @param Repo             $repo
     * @param ISession         $session
     * @param IEventDispatcher $eventDispatcher
     * @param FormFactory      $formFactory
     * @param AssetManager     $assetManager
     */
    public function __construct(
        FlashService $flashService,
        ITranslator $translator,
        UrlGenerator $urlGenerator,
        LoggerInterface $logger,
        Repo $repo,
        ISession $session,
        IEventDispatcher $eventDispatcher,
        FormFactory $formFactory,
        AssetManager $assetManager
    ) {
        parent::__construct(
            $flashService,
            $translator,
            $urlGenerator,
            $logger,
            $repo,
            $session,
            $formFactory,
            $eventDispatcher
        );

        $this->formFactory  = $formFactory;
        $this->assetManager = $assetManager;
    }

    /**
     * @param string $entityId
     *
     * @return Entity
     */
    protected function createEntity(string $entityId): IStringerEntity
    {
        return new Entity($entityId, '', '', '', false, false, false, false, false, false);
    }

    /**
     * @param IStringerEntity|null $entity
     *
     * @throws \League\Flysystem\FileNotFoundException
     */
    protected function addCustomAssets(?IStringerEntity $entity = null)
    {
        parent::addCustomAssets($entity);

        if (!($entity instanceof Entity)) {
            return;
        }

        $styles = $this->getResourceName(static::RESOURCE_DEFAULT);
        $this->assetManager->addCss($styles, '/admin-assets/vendor/trumbowyg/ui/trumbowyg.css');
        $this->assetManager->addCss($styles, '/admin-assets/css/list.css');

        $footer = $this->getResourceName(static::RESOURCE_FOOTER);
        $this->assetManager->addJs($footer, '/admin-assets/vendor/trumbowyg/trumbowyg.js');
        $this->assetManager->addJs($footer, '/admin-assets/vendor/trumbowyg/langs/hu.js');
        $this->assetManager->addJs($footer, '/admin-assets/js/editor.js');
        $this->assetManager->addJs($footer, '/admin-assets/js/list.js');
        $this->assetManager->addJs($footer, '/admin-assets/js/hideable-container.js');
        $this->assetManager->addJs($footer, '/admin-assets/js/semi-auto.js');
        $this->assetManager->addJs($footer, '/admin-assets/js/required.js');
        $this->assetManager->addJs($footer, '/admin-assets/js/validation.js');
    }
}
