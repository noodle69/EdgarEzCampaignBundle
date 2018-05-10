<?php

namespace Edgar\EzCampaign\Data;

use EzSystems\RepositoryForms\Data\NewnessCheckable;
use Edgar\EzCampaign\Values\API\Folder;
use Edgar\EzCampaign\Values\FolderCreateStruct;

/**
 * @property-read Folder $campaignFolder
 */
class FolderCreateData extends FolderCreateStruct implements NewnessCheckable
{
    use FolderDataTrait;

    public function isNew()
    {
        return true;
    }
}
