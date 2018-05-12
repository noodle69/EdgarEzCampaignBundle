<?php

namespace Edgar\EzCampaign\Values\Core;

class Reports extends \Edgar\EzCampaign\Values\API\Reports
{
    public function getCampaign()
    {
        return $this->campaign;
    }
}
