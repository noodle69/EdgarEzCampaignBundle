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

    protected $web_id;

    protected $type;

    protected $create_time;

    protected $archive_url;

    protected $long_archive_url;

    protected $status;

    protected $emails_sent;

    protected $send_time;

    protected $content_type;

    protected $needs_block_refresh;

    protected $recipients;

    protected $settings;

    protected $tracking;

    protected $delivery_status;
}
