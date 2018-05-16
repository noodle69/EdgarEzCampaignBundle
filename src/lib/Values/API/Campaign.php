<?php

namespace Edgar\EzCampaign\Values\API;

use Edgar\EzUISites\Data\SiteData;
use eZ\Publish\API\Repository\Values\ValueObject;
use eZ\Publish\Core\Repository\Values\Content\Location;

abstract class Campaign extends ValueObject
{
    protected $id;

    protected $list_id;

    protected $folder_id;

    /** @var Location */
    protected $content;

    /** @var SiteData */
    protected $site;

    protected $subject_line;

    protected $title;

    protected $from_name;

    protected $reply_to;
}
