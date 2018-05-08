<?php

namespace Edgar\EzCampaign\Data;

use EzSystems\RepositoryForms\Data\NewnessChecker;
use Edgar\EzCampaign\Values\CampaignListUpdateStruct;

class CampaignListUpdateData extends CampaignListUpdateStruct
{
    use CampaignListDataTrait, NewnessChecker;

    protected function getIdValue()
    {
        return $this->campaignList->id;
    }

    protected function getIdentifierValue()
    {
        return null;
    }
}
