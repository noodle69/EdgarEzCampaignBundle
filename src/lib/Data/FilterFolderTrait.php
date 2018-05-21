<?php

namespace Edgar\EzCampaign\Data;

use Edgar\EzCampaign\Values\Core\FilterFolder;

trait FilterFolderTrait
{
    /**
     * @var FilterFolder
     */
    protected $filterFolder;

    public function setFilterFolder(FilterFolder $filterFolder)
    {
        $this->$filterFolder = $filterFolder;
    }
}
