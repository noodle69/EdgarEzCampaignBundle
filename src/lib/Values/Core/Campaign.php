<?php

namespace Edgar\EzCampaign\Values\Core;

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

    public function getFolderId()
    {
        return $this->folder_id;
    }
}
