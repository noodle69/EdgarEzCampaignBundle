<?php

namespace Edgar\EzCampaign\Data;

use Edgar\EzCampaign\Values\API\Campaign;

trait CampaignDataTrait
{
    /**
     * @var Campaign $campaign
     */
    protected $campaign;

    public function setCampaign(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function getId()
    {
        return $this->campaign ? $this->campaign->id : null;
    }
}
