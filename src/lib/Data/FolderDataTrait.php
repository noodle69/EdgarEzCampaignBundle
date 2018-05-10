<?php

namespace Edgar\EzCampaign\Data;

use Edgar\EzCampaign\Values\API\Folder;

trait FolderDataTrait
{
    /**
     * @var Folder $campaignFolder
     */
    protected $campaignFolder;

    public function setCampaignFolder(Folder $campaignFolder)
    {
        $this->campaignFolder = $campaignFolder;
    }

    public function getId()
    {
        return $this->campaignFolder ? $this->campaignFolder->id : null;
    }
}
