<?php

namespace Edgar\EzCampaign\Data;

class FoldersDeleteData
{
    /** @var array|null */
    protected $folders;

    /**
     * @param array|null $campaigns
     */
    public function __construct(array $folders = [])
    {
        $this->folders = $folders;
    }

    /**
     * @return array|null
     */
    public function getFolders(): ?array
    {
        return $this->folders;
    }

    /**
     * @param array|null $campaigns
     */
    public function setFolders(?array $folders)
    {
        $this->folders = $folders;
    }
}
