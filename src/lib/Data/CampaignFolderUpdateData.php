<?php

namespace Edgar\EzCampaign\Data;

use EzSystems\RepositoryForms\Data\NewnessChecker;
use Edgar\EzCampaign\Values\CampaignFolderUpdateStruct;

class CampaignFolderUpdateData extends CampaignFolderUpdateStruct
{
    use CampaignFolderDataTrait, NewnessChecker;

    protected function getIdValue()
    {
        return $this->campaignFolder->id;
    }

    protected function getIdentifierValue()
    {
        return null;
    }
}
