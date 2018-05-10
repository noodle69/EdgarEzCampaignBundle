<?php

namespace Edgar\EzCampaign\Data;

use EzSystems\RepositoryForms\Data\NewnessCheckable;
use Edgar\EzCampaign\Values\ListCreateStruct;

class ListCreateData extends ListCreateStruct implements NewnessCheckable
{
    use ListDataTrait;

    public function isNew()
    {
        return true;
    }
}
