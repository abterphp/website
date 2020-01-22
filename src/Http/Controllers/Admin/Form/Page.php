<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Admin\Form;

use AbterPhp\Admin\Config\Routes;
use AbterPhp\Admin\Constant\Env as AdminEnv;
use AbterPhp\Admin\Http\Controllers\Admin\FormAbstract;
use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\Framework\Config\EnvReader;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Website\Domain\Entities\Page as Entity;
use AbterPhp\Website\Form\Factory\Page as FormFactory;
use AbterPhp\Website\Orm\PageRepo as Repo;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Psr\Log\LoggerInterface;

/**
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 */
class Page extends FormAbstract
{
    const ENTITY_PLURAL   = 'pages';
    const ENTITY_SINGULAR = 'page';

    const ENTITY_TITLE_SINGULAR = 'website:page';
    const ENTITY_TITLE_PLURAL   = 'website:pages';

    const ROUTING_PATH = 'pages';

    /** @var AssetManager */
    protected $assetManager;

    /** @var EnvReader */
    protected $envReader;

    /** @var string */
    protected $resource = 'pages';

    /**
     * Page constructor.
     *
     * @param FlashService     $flashService
     * @param ITranslator      $translator
     * @param UrlGenerator     $urlGenerator
     * @param LoggerInterface  $logger
     * @param Repo             $repo
     * @param ISession         $session
     * @param FormFactory      $formFactory
     * @param IEventDispatcher $eventDispatcher
     * @param AssetManager     $assetManager
     * @param EnvReader        $envReader
     */
    public function __construct(
        FlashService $flashService,
        ITranslator $translator,
        UrlGenerator $urlGenerator,
        LoggerInterface $logger,
        Repo $repo,
        ISession $session,
        FormFactory $formFactory,
        IEventDispatcher $eventDispatcher,
        AssetManager $assetManager,
        EnvReader $envReader
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
        $this->envReader    = $envReader;
    }

    /**
     * @param string $entityId
     *
     * @return Entity
     */
    protected function createEntity(string $entityId): IStringerEntity
    {
        return new Entity((string)$entityId, '', '', '', '', '', false);
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

        $jsContent = sprintf(
            "var clientId=\"%s\";\nvar editorFileUploadPath=\"%s%s\";",
            $this->envReader->get(AdminEnv::UPLOAD_CLIENT_ID),
            Routes::getApiBasePath(),
            '/editor-file-upload'
        );

        $styles = $this->getResourceName(static::RESOURCE_DEFAULT);
        $this->assetManager->addCss($styles, '/admin-assets/vendor/trumbowyg/ui/trumbowyg.css');
        $this->assetManager->addCss($styles, '/admin-assets/vendor/trumbowyg/plugins/table/ui/trumbowyg.table.css');

        $footer = $this->getResourceName(static::RESOURCE_FOOTER);
        $this->assetManager->addJs($footer, '/admin-assets/vendor/trumbowyg/trumbowyg.js');
        $this->assetManager->addJs($footer, '/admin-assets/vendor/trumbowyg/langs/hu.js');
        $this->assetManager->addJs($footer, '/admin-assets/vendor/trumbowyg/plugins/table/trumbowyg.table.js');
        $this->assetManager->addJs($footer, '/admin-assets/vendor/trumbowyg/plugins/upload/trumbowyg.upload.js');
        $this->assetManager->addJs($footer, '/admin-assets/js/editor.js');
        $this->assetManager->addJs($footer, '/admin-assets/js/countable-textarea.js');
        $this->assetManager->addJs($footer, '/admin-assets/js/hideable-container.js');
        $this->assetManager->addJs($footer, '/admin-assets/js/layout-or-id.js');
        $this->assetManager->addJs($footer, '/admin-assets/js/identifier.js');
        $this->assetManager->addJs($footer, '/admin-assets/js/semi-auto.js');
        $this->assetManager->addJs($footer, '/admin-assets/js/required.js');
        $this->assetManager->addJs($footer, '/admin-assets/js/validation.js');
        $this->assetManager->addJs($footer, '/admin-assets/js/page.js');
        $this->assetManager->addJsContent($footer, $jsContent);
    }
}
