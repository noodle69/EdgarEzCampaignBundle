<?php

namespace Edgar\EzCampaign\Data;

use EzSystems\RepositoryForms\Data\NewnessCheckable;
use Edgar\EzCampaign\Values\CampaignListCreateStruct;

class CampaignListCreateData extends CampaignListCreateStruct implements NewnessCheckable
{
    use CampaignListDataTrait;

    public function isNew()
    {
        return true;
    }
}
