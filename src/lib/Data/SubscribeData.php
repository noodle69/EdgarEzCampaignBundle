<?php

namespace Edgar\EzCampaign\Data;

use Edgar\EzCampaign\Values\SubscribeStruct;
use EzSystems\RepositoryForms\Data\NewnessCheckable;

class SubscribeData extends SubscribeStruct implements NewnessCheckable
{
    use SubscribeDataTrait;

    /**
     * @return bool
     */
    public function isNew()
    {
        return true;
    }
}
