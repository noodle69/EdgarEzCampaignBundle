<?php

namespace Edgar\EzCampaign\Data;

use EzSystems\RepositoryForms\Data\NewnessCheckable;
use Edgar\EzCampaign\Values\FolderCreateStruct;

class FolderCreateData extends FolderCreateStruct implements NewnessCheckable
{
    use FolderDataTrait;

    public function isNew()
    {
        return true;
    }
}
