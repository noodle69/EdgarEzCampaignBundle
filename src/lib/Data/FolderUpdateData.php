<?php

namespace Edgar\EzCampaign\Data;

use EzSystems\RepositoryForms\Data\NewnessChecker;
use Edgar\EzCampaign\Values\FolderUpdateStruct;

class FolderUpdateData extends FolderUpdateStruct
{
    use FolderDataTrait, NewnessChecker;

    protected function getIdValue()
    {
        return $this->campaignFolder->id;
    }

    protected function getIdentifierValue()
    {
        return null;
    }
}
