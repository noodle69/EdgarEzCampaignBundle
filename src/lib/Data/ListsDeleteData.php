<?php

namespace Edgar\EzCampaign\Data;

class ListsDeleteData
{
    /** @var array|null */
    protected $lists;

    /**
     * @param array|null $lists
     */
    public function __construct(array $lists = [])
    {
        $this->lists = $lists;
    }

    /**
     * @return array|null
     */
    public function getLists(): ?array
    {
        return $this->lists;
    }

    /**
     * @param array|null $lists
     */
    public function setLists(?array $lists)
    {
        $this->lists = $lists;
    }
}
