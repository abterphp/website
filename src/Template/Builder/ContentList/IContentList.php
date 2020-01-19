<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Builder\ContentList;

class IContentList
{
    const LABEL   = 'label';
    const CONTENT = 'body';
    const IMAGE   = 'image';

    const LIST_TAG    = 'list-tag';
    const ITEM_TAG    = 'item-tag';
    const LABEL_TAG   = 'label-tag';
    const CONTENT_TAG = 'content-tag';
    const IMAGE_TAG   = 'image-tag';

    const LIST_CLASS    = 'list-class';
    const ITEM_CLASS    = 'item-class';
    const LABEL_CLASS   = 'label-class';
    const CONTENT_CLASS = 'content-class';
    const IMAGE_CLASS   = 'image-class';

    const WITH_LABEL_OPTION  = 'with-label';
    const WITH_IMAGES_OPTION = 'with-images';
}
