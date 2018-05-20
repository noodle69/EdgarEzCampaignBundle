<?php

namespace Edgar\EzCampaign\Data;

use Edgar\EzCampaign\Values\ReportsStruct;
use EzSystems\RepositoryForms\Data\NewnessCheckable;

class ReportsData extends ReportsStruct implements NewnessCheckable
{
    use ReportsDataTrait;

    /**
     * @return bool
     */
    public function isNew()
    {
        return true;
    }
}
