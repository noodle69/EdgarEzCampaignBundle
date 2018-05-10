<?php

namespace Edgar\EzCampaign\Values\Core;

class CampaignList extends \Edgar\EzCampaign\Values\API\CampaignList
{
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getState()
    {
        return $this->state;
    }

    public function getZip()
    {
        return $this->zip;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getPermissionReminder()
    {
        return $this->permission_reminder;
    }

    public function getFromName()
    {
        return $this->from_name;
    }

    public function getFromEmail()
    {
        return $this->from_email;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function getWebId()
    {
        return $this->web_id;
    }
}
