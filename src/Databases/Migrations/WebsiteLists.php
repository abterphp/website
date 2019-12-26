<?php

declare(strict_types=1);

namespace AbterPhp\Website\Databases\Migrations;

use AbterPhp\Framework\Databases\Migrations\BaseMigration;
use DateTime;

class WebsiteLists extends BaseMigration
{
    const FILENAME = 'website-lists.sql';

    /**
     * Gets the creation date, which is used for ordering
     *
     * @return DateTime The date this migration was created
     */
    public static function getCreationDate(): DateTime
    {
        return DateTime::createFromFormat(DateTime::ATOM, '2019-12-13T18:52:00+00:00');
    }
}
