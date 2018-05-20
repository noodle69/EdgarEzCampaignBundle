<?php

namespace Edgar\EzCampaign\Values\Core;

use Edgar\EzUISites\Data\SiteData;
use eZ\Publish\Core\Repository\Values\Content\Location;

class CampaignContent extends \Edgar\EzCampaign\Values\API\CampaignContent
{
    public function getContent(): ?Location
    {
        return $this->content;
    }

    public function setContent(?Location $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getSite(): ?SiteData
    {
        return $this->site;
    }

    public function setSite(?SiteData $site): self
    {
        $this->site = $site;

        return $this;
    }
}
