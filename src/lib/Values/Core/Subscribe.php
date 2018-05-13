<?php

namespace Edgar\EzCampaign\Values\Core;

class Subscribe extends \Edgar\EzCampaign\Values\API\Subscribe
{
    public function getEmail()
    {
        return $this->email;
    }
}
