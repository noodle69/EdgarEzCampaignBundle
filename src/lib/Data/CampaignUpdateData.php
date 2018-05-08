<?php

namespace Edgar\EzCampaign\Data;

use EzSystems\RepositoryForms\Data\NewnessChecker;
use Edgar\EzCampaign\Values\CampaignUpdateStruct;

class CampaignUpdateData extends CampaignUpdateStruct
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
