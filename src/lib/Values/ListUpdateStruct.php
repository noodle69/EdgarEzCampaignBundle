<?php

namespace Edgar\EzCampaign\Values;

class ListUpdateStruct extends ListStruct
{
    public $name = null;

    public $company = null;

    public $address = null;

    public $address2 = null;

    public $city = null;

    public $state = null;

    public $zip = null;

    public $country = null;

    public $phone;

    public $permission_reminder = null;

    public $use_archive_bar = null;

    public $from_name = null;

    public $from_email = null;

    public $subject = null;

    public $language = null;

    public $notify_on_subscribe = null;

    public $notify_on_unsubscribe = null;

    public $visibility = null;
}
