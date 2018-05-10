<?php

namespace Edgar\EzCampaign\Data;

use Edgar\EzCampaign\Values\API\CampaignList;

trait ListDataTrait
{
    /**
     * @var CampaignList $campaignList
     */
    protected $campaignList;

    public function setCampaignList(CampaignList $campaignList)
    {
        $this->campaignList = $campaignList;
    }

    public function getId()
    {
        return $this->campaignList ? $this->campaignList->id : null;
    }
}
