<?php

namespace Edgar\EzCampaign\Data;

class CampaignsDeleteData
{
    /** @var array|null */
    protected $campaigns;

    /**
     * @param array|null $campaigns
     */
    public function __construct(array $campaigns = [])
    {
        $this->campaigns = $campaigns;
    }

    /**
     * @return array|null
     */
    public function getCampaigns(): ?array
    {
        return $this->campaigns;
    }

    /**
     * @param array|null $campaigns
     */
    public function setCampaigns(?array $campaigns)
    {
        $this->campaigns = $campaigns;
    }
}
