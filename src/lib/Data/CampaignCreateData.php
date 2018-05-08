<?php

namespace Edgar\EzCampaign\Data;

use EzSystems\RepositoryForms\Data\NewnessCheckable;
use Edgar\EzCampaign\Values\CampaignCreateStruct;

class CampaignCreateData extends CampaignCreateStruct implements NewnessCheckable
{
    use CampaignDataTrait;

    public function isNew()
    {
        return true;
    }
}
