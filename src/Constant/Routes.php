<?php

declare(strict_types=1);

namespace AbterPhp\Website\Constant;

use AbterPhp\Framework\Constant\Routes as FrameworkRoutes;

class Routes extends FrameworkRoutes
{
    const ROUTE_INDEX    = 'index';
    const ROUTE_FALLBACK = 'fallback';
    const ROUTE_NOPE     = 'nope';
    const ROUTE_404      = '404';

    const PATH_INDEX    = '/';
    const PATH_FALLBACK = '/:identifier';
    const PATH_NOPE     = '/nope';
    const PATH_404      = '/:anything';

    const VAR_ANYTHING = 'anything';

    const ROUTE_PAGES        = 'pages';
    const ROUTE_PAGES_NEW    = 'pages-new';
    const ROUTE_PAGES_EDIT   = 'pages-edit';
    const ROUTE_PAGES_DELETE = 'pages-delete';
    const PATH_PAGES         = '/pages';
    const PATH_PAGES_NEW     = '/pages/new';
    const PATH_PAGES_EDIT    = '/pages/:id/edit';
    const PATH_PAGES_DELETE  = '/pages/:id/delete';

    const ROUTE_BLOCKS        = 'blocks';
    const ROUTE_BLOCKS_NEW    = 'blocks-new';
    const ROUTE_BLOCKS_EDIT   = 'blocks-edit';
    const ROUTE_BLOCKS_DELETE = 'blocks-delete';
    const PATH_BLOCKS         = '/blocks';
    const PATH_BLOCKS_NEW     = '/blocks/new';
    const PATH_BLOCKS_EDIT    = '/blocks/:id/edit';
    const PATH_BLOCKS_DELETE  = '/blocks/:id/delete';

    const ROUTE_BLOCK_LAYOUTS        = 'block-layouts';
    const ROUTE_BLOCK_LAYOUTS_NEW    = 'block-layouts-new';
    const ROUTE_BLOCK_LAYOUTS_EDIT   = 'block-layouts-edit';
    const ROUTE_BLOCK_LAYOUTS_DELETE = 'block-layouts-delete';
    const PATH_BLOCK_LAYOUTS         = '/block-layout';
    const PATH_BLOCK_LAYOUTS_NEW     = '/block-layout/new';
    const PATH_BLOCK_LAYOUTS_EDIT    = '/block-layout/:id/edit';
    const PATH_BLOCK_LAYOUTS_DELETE  = '/block-layout/:id/delete';

    const ROUTE_PAGE_LAYOUTS        = 'page-layouts';
    const ROUTE_PAGE_LAYOUTS_NEW    = 'page-layouts-new';
    const ROUTE_PAGE_LAYOUTS_EDIT   = 'page-layouts-edit';
    const ROUTE_PAGE_LAYOUTS_DELETE = 'page-layouts-delete';
    const PATH_PAGE_LAYOUTS         = '/page-layouts';
    const PATH_PAGE_LAYOUTS_NEW     = '/page-layouts/new';
    const PATH_PAGE_LAYOUTS_EDIT    = '/page-layouts/:id/edit';
    const PATH_PAGE_LAYOUTS_DELETE  = '/page-layouts/:id/delete';

    const ROUTE_PAGE_CATEGORIES        = 'page-categories';
    const ROUTE_PAGE_CATEGORIES_NEW    = 'page-categories-new';
    const ROUTE_PAGE_CATEGORIES_EDIT   = 'page-categories-edit';
    const ROUTE_PAGE_CATEGORIES_DELETE = 'page-categories-delete';
    const PATH_PAGE_CATEGORIES         = '/page-categories';
    const PATH_PAGE_CATEGORIES_NEW     = '/page-categories/new';
    const PATH_PAGE_CATEGORIES_EDIT    = '/page-categories/:id/edit';
    const PATH_PAGE_CATEGORIES_DELETE  = '/page-categories/:id/delete';

    const ROUTE_CONTENT_LISTS        = 'lists';
    const ROUTE_CONTENT_LISTS_NEW    = 'lists-new';
    const ROUTE_CONTENT_LISTS_EDIT   = 'lists-edit';
    const ROUTE_CONTENT_LISTS_DELETE = 'lists-delete';
    const PATH_CONTENT_LISTS         = '/lists';
    const PATH_CONTENT_LISTS_NEW     = '/lists/new';
    const PATH_CONTENT_LISTS_EDIT    = '/lists/:id/edit';
    const PATH_CONTENT_LISTS_DELETE  = '/lists/:id/delete';
}
