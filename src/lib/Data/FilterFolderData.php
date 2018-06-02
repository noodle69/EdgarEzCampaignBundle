<?php

namespace Edgar\EzCampaign\Data;

use Edgar\EzCampaign\Values\FilterFolderStruct;
use EzSystems\RepositoryForms\Data\NewnessCheckable;

class FilterFolderData extends FilterFolderStruct implements NewnessCheckable
{
    use FilterFolderTrait;

    /**
     * @return bool
     */
    public function isNew()
    {
        return true;
    }

    /**
     * @return mixed
     */
    public function getFolder()
    {
        return $this->folder;
    }
}
