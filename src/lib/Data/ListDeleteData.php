<?php

namespace Edgar\EzCampaign\Data;

use Edgar\EzCampaign\Values\ListDeleteStruct;
use EzSystems\RepositoryForms\Data\NewnessChecker;

class ListDeleteData extends ListDeleteStruct
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
