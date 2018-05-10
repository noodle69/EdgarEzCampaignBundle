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

    public function getWebId()
    {
        return $this->web_id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getCreateTime()
    {
        return $this->create_time;
    }

    public function getArchiveUrl()
    {
        return $this->archive_url;
    }

    public function getLongArchiveUrl()
    {
        return $this->long_archive_url;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getEmailsSent()
    {
        return $this->emails_sent;
    }

    public function getSendTime()
    {
        return $this->send_time;
    }

    public function getContentType()
    {
        return $this->content_type;
    }

    public function getNeedsBlockRefresh()
    {
        return $this->needs_block_refresh;
    }

    public function getRecipients()
    {
        return $this->recipients;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function getTracking()
    {
        return $this->tracking;
    }

    public function getDeliveryStatus()
    {
        return $this->delivery_status;
    }
}
