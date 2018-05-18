<?php

namespace Edgar\EzCampaign\Values\API;

use eZ\Publish\API\Repository\Values\ValueObject;
use eZ\Publish\Core\FieldType\Country\Value as CountryValue;

abstract class CampaignList extends ValueObject
{
    protected $id;

    protected $name;

    protected $company;

    protected $address;

    protected $address2;

    protected $city;

    protected $state;

    protected $zip;

    /** @var CountryValue */
    protected $country;

    protected $phone;

    protected $permission_reminder;

    protected $use_archive_bar;

    protected $from_name;

    protected $from_email;

    protected $subject;

    protected $language;

    protected $notify_on_subscribe;

    protected $notify_on_unsubscribe;

    protected $visibility;
}
