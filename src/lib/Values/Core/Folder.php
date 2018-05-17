<?php

namespace Edgar\EzCampaign\Values\Core;

class Folder extends \Edgar\EzCampaign\Values\API\Folder
{
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }
}
