<?php

namespace Edgar\EzCampaign\Values\API;

use eZ\Publish\API\Repository\Values\ValueObject;

abstract class Campaign extends ValueObject
{
    protected $id;

    protected $list_id;

    protected $subject_line;

    protected $title;

    protected $from_name;

    protected $reply_to;

    protected $folder_id;
}
