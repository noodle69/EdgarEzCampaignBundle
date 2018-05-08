<?php

namespace Edgar\EzCampaign\Data;

use EzSystems\RepositoryForms\Data\NewnessCheckable;
use Edgar\EzCampaign\Values\API\CampaignFolder;
use Edgar\EzCampaign\Values\CampaignFolderCreateStruct;

/**
 * @property-read CampaignFolder $campaignFolder
 */
class CampaignFolderCreateData extends CampaignFolderCreateStruct implements NewnessCheckable
{
    use CampaignFolderDataTrait;

    public function isNew()
    {
        return true;
    }
}
