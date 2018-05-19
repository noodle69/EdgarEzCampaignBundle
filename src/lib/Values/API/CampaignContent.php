<?php

namespace Edgar\EzCampaign\Values\API;

use Edgar\EzUISites\Data\SiteData;
use eZ\Publish\API\Repository\Values\ValueObject;
use eZ\Publish\Core\Repository\Values\Content\Location;

abstract class CampaignContent extends ValueObject
{
    /** @var Location */
    protected $content;

    /** @var SiteData */
    protected $site;
}
