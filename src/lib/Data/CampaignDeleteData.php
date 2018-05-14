<?php

namespace Edgar\EzCampaign\Data;

use Edgar\EzCampaign\Values\CampaignDeleteStruct;
use EzSystems\RepositoryForms\Data\NewnessChecker;

class CampaignDeleteData extends CampaignDeleteStruct
{
    use CampaignDataTrait, NewnessChecker;

    protected function getIdValue()
    {
        return $this->campaign->id;
    }

    protected function getIdentifierValue()
    {
        return null;
    }
}
