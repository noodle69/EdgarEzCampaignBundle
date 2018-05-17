<?php

namespace Edgar\EzCampaign\Values;

use Edgar\EzCampaign\Values\Core\CampaignList;
use Edgar\EzCampaign\Values\Core\Folder;
use Edgar\EzUISites\Data\SiteData;
use eZ\Publish\Core\Repository\Values\Content\Location;

class CampaignCreateStruct extends CampaignStruct
{
    /** @var CampaignList */
    public $list_id;

    /** @var Folder */
    public $folder_id;

    /** @var Location */
    public $content;

    /** @var SiteData */
    public $site;

    public $subject_line;

    public $title;

    public $from_name;

    public $reply_to;
}
