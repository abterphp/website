<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Admin\Form;

use AbterPhp\Admin\Http\Controllers\Admin\FormAbstract;
use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Website\Domain\Entities\PageLayout as Entity;
use AbterPhp\Website\Form\Factory\PageLayout as FormFactory;
use AbterPhp\Website\Orm\PageLayoutRepo as Repo;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Psr\Log\LoggerInterface;

class PageLayout extends FormAbstract
{
    const ENTITY_PLURAL   = 'pageLayouts';
    const ENTITY_SINGULAR = 'pageLayout';

    const ENTITY_TITLE_SINGULAR = 'website:pageLayout';
    const ENTITY_TITLE_PLURAL   = 'website:pageLayouts';

    const ROUTING_PATH = 'page-layouts';

    /** @var AssetManager */
    protected $assetManager;

    /** @var string */
    protected $resource = 'page_layouts';

    /**
     * PageLayout constructor.
     *
     * @param FlashService     $flashService
     * @param ITranslator      $translator
     * @param UrlGenerator     $urlGenerator
     * @param LoggerInterface  $logger
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
        LoggerInterface $logger,
        Repo $repo,
        ISession $session,
        FormFactory $formFactory,
        AssetManager $assetManager,
        IEventDispatcher $eventDispatcher
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

        $this->assetManager = $assetManager;
    }

    /**
     * @param string $entityId
     *
     * @return Entity
     */
    protected function createEntity(string $entityId): IStringerEntity
    {
        return new Entity($entityId, '', '', '', '', new Entity\Assets('', '', '', [], []));
    }

    /**
     * @param IStringerEntity|null $entity
     */
    protected function addCustomAssets(?IStringerEntity $entity = null)
    {
        parent::addCustomAssets($entity);

        if (!($entity instanceof Entity)) {
            return;
        }

        $footer = $this->getResourceName(static::RESOURCE_FOOTER);
        $this->assetManager->addJs($footer, '/admin-assets/js/hideable-container.js');
        $this->assetManager->addJs($footer, '/admin-assets/js/semi-auto.js');
        $this->assetManager->addJs($footer, '/admin-assets/js/required.js');
        $this->assetManager->addJs($footer, '/admin-assets/js/validation.js');
    }
}
