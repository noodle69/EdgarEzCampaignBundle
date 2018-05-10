<?php

namespace Edgar\EzCampaign\Data;

use EzSystems\RepositoryForms\Data\NewnessChecker;
use Edgar\EzCampaign\Values\ListUpdateStruct;

class ListUpdateData extends ListUpdateStruct
{
    use ListDataTrait, NewnessChecker;

    protected function getIdValue()
    {
        return $this->campaignList->id;
    }

    protected function getIdentifierValue()
    {
        return null;
    }
}
