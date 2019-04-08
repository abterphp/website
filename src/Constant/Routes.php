<?php

declare(strict_types=1);

namespace AbterPhp\Website\Constant;

class Routes
{
    const ROUTE_HOME       = 'home';
    const ROUTE_PAGE_OTHER = 'other-page';
    const ROUTE_NOPE       = 'nope';
    const ROUTE_404        = '404';

    const PATH_HOME = '/';
    const PATH_PAGE = '/:identifier';
    const PATH_NOPE = '/nope';
    const PATH_404  = '/:anything';

    const VAR_ANYTHING = 'anything';

    const ROUTE_PAGES        = 'pages';
    const ROUTE_PAGES_NEW    = 'pages-new';
    const ROUTE_PAGES_EDIT   = 'pages-edit';
    const ROUTE_PAGES_DELETE = 'pages-delete';
    const PATH_PAGES         = '/page';
    const PATH_PAGES_NEW     = '/page/new';
    const PATH_PAGES_EDIT    = '/page/:id/edit';
    const PATH_PAGES_DELETE  = '/page/:id/delete';

    const ROUTE_BLOCKS        = 'blocks';
    const ROUTE_BLOCKS_NEW    = 'blocks-new';
    const ROUTE_BLOCKS_EDIT   = 'blocks-edit';
    const ROUTE_BLOCKS_DELETE = 'blocks-delete';
    const PATH_BLOCKS         = '/block';
    const PATH_BLOCKS_NEW     = '/block/new';
    const PATH_BLOCKS_EDIT    = '/block/:id/edit';
    const PATH_BLOCKS_DELETE  = '/block/:id/delete';

    const ROUTE_BLOCK_LAYOUTS        = 'blocklayouts';
    const ROUTE_BLOCK_LAYOUTS_NEW    = 'blocklayouts-new';
    const ROUTE_BLOCK_LAYOUTS_EDIT   = 'blocklayouts-edit';
    const ROUTE_BLOCK_LAYOUTS_DELETE = 'blocklayouts-delete';
    const PATH_BLOCK_LAYOUTS         = '/blocklayout';
    const PATH_BLOCK_LAYOUTS_NEW     = '/blocklayout/new';
    const PATH_BLOCK_LAYOUTS_EDIT    = '/blocklayout/:id/edit';
    const PATH_BLOCK_LAYOUTS_DELETE  = '/blocklayout/:id/delete';

    const ROUTE_PAGE_LAYOUTS        = 'pagelayouts';
    const ROUTE_PAGE_LAYOUTS_NEW    = 'pagelayouts-new';
    const ROUTE_PAGE_LAYOUTS_EDIT   = 'pagelayouts-edit';
    const ROUTE_PAGE_LAYOUTS_DELETE = 'pagelayouts-delete';
    const PATH_PAGE_LAYOUTS         = '/pagelayout';
    const PATH_PAGE_LAYOUTS_NEW     = '/pagelayout/new';
    const PATH_PAGE_LAYOUTS_EDIT    = '/pagelayout/:id/edit';
    const PATH_PAGE_LAYOUTS_DELETE  = '/pagelayout/:id/delete';
}
