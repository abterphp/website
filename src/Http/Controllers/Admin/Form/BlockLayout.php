<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Admin\Form;

use AbterPhp\Admin\Http\Controllers\Admin\FormAbstract;
use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Website\Domain\Entities\BlockLayout as Entity;
use AbterPhp\Website\Form\Factory\BlockLayout as FormFactory;
use AbterPhp\Website\Orm\BlockLayoutRepo as Repo;
use League\Flysystem\FilesystemException;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Psr\Log\LoggerInterface;

class BlockLayout extends FormAbstract
{
    const ENTITY_PLURAL   = 'blockLayouts';
    const ENTITY_SINGULAR = 'blockLayout';

    const ENTITY_TITLE_SINGULAR = 'website:blockLayout';
    const ENTITY_TITLE_PLURAL   = 'website:blockLayouts';

    const ROUTING_PATH = 'block-layouts';

    /** @var AssetManager */
    protected $assetManager;

    /** @var string */
    protected $resource = 'block_layouts';

    /**
     * BlockLayout constructor.
     *
     * @param FlashService     $flashService
     * @param LoggerInterface  $logger
     * @param ITranslator      $translator
     * @param UrlGenerator     $urlGenerator
     * @param Repo             $repo
     * @param ISession         $session
     * @param FormFactory      $formFactory
     * @param IEventDispatcher $eventDispatcher
     * @param AssetManager     $assetManager
     */
    public function __construct(
        FlashService $flashService,
        LoggerInterface $logger,
        ITranslator $translator,
        UrlGenerator $urlGenerator,
        Repo $repo,
        ISession $session,
        FormFactory $formFactory,
        IEventDispatcher $eventDispatcher,
        AssetManager $assetManager
    ) {
        parent::__construct(
            $flashService,
            $logger,
            $translator,
            $urlGenerator,
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
        return new Entity($entityId, '', '', '');
    }

    /**
     * @param IStringerEntity|null $entity
     *
     * @throws FilesystemException
     */
    protected function addCustomAssets(?IStringerEntity $entity = null)
    {
        parent::addCustomAssets($entity);

        if (!($entity instanceof Entity)) {
            return;
        }

        $footer = $this->getResourceName(static::RESOURCE_FOOTER);
        $this->assetManager->addJs($footer, '/admin-assets/js/semi-auto.js');
        $this->assetManager->addJs($footer, '/admin-assets/js/required.js');
        $this->assetManager->addJs($footer, '/admin-assets/js/validation.js');
    }
}
