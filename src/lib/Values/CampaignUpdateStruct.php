<?php

namespace Edgar\EzCampaign\Values;

use Edgar\EzUISites\Data\SiteData;
use eZ\Publish\Core\Repository\Values\Content\Location;

class CampaignUpdateStruct extends CampaignStruct
{
    public $list_id = null;

    public $folder_id = null;

    /** @var Location */
    public $content = null;

    /** @var SiteData */
    public $site = null;

    public $subject_line = null;

    public $title = null;

    public $from_name = null;

    public $reply_to = null;
}
