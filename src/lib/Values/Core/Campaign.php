<?php

namespace Edgar\EzCampaign\Values\Core;

use Edgar\EzUISites\Data\SiteData;
use eZ\Publish\Core\Repository\Values\Content\Location;

class Campaign extends \Edgar\EzCampaign\Values\API\Campaign
{
    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getListId(): ?string
    {
        return $this->list_id;
    }

    public function setListId($list): self
    {
        if (is_object($list)) {
            $this->list_id = $list->id;
        } else {
            $this->list_id = $list;
        }

        return $this;
    }

    public function getList(): ?CampaignList
    {
        return $this->list;
    }

    public function setList(CampaignList $list): self
    {
        $this->list = $list;
        return $this;
    }

    public function getFolderId(): ?string
    {
        return $this->folder_id;
    }

    public function setFolderId($folder): self
    {
        if (is_object($folder)) {
            $this->folder_id = $folder->id;
        } else {
            $this->folder_id = $folder;
        }

        return $this;
    }

    public function getFolder(): ?Folder
    {
        return $this->folder;
    }

    public function setFolder(Folder $folder): self
    {
        $this->folder = $folder;
        return $this;
    }

    public function getContent(): ?Location
    {
        return $this->content;
    }

    public function setContent(?Location $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getSite(): ?SiteData
    {
        return $this->site;
    }

    public function setSite(?SiteData $site): self
    {
        $this->site = $site;
        return $this;
    }

    public function getSubjectLine(): ?string
    {
        return $this->subject_line;
    }

    public function setSubjectLine(string $subjectLine): self
    {
        $this->subject_line = $subjectLine;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getFromName(): ?string
    {
        return $this->from_name;
    }

    public function setFromName(string $fromName): self
    {
        $this->from_name = $fromName;
        return $this;
    }

    public function getReplyTo(): ?string
    {
        return $this->reply_to;
    }

    public function setReplyTo(string $replyTo): self
    {
        $this->reply_to = $replyTo;
        return $this;
    }
}
