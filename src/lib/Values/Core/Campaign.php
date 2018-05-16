<?php

namespace Edgar\EzCampaign\Values\Core;

use Edgar\EzUISites\Data\SiteData;
use eZ\Publish\Core\Repository\Values\Content\Location;

class Campaign extends \Edgar\EzCampaign\Values\API\Campaign
{
    public function getId()
    {
        return $this->id;
    }

    public function getListId()
    {
        return $this->list_id;
    }

    public function getFolderId()
    {
        return $this->folder_id;
    }

    public function getContent(): Location
    {
        return $this->content;
    }

    public function getSite(): SiteData
    {
        return $this->site;
    }

    public function getSubjectLine()
    {
        return $this->subject_line;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getFromName()
    {
        return $this->from_name;
    }

    public function getReplyTo()
    {
        return $this->reply_to;
    }
}
