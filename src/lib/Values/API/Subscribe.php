<?php

namespace Edgar\EzCampaign\Values\API;

use eZ\Publish\API\Repository\Values\ValueObject;

abstract class Subscribe extends ValueObject
{
    protected $email;
}
