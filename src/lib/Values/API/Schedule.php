<?php

namespace Edgar\EzCampaign\Values\API;

use eZ\Publish\API\Repository\Values\ValueObject;
use eZ\Publish\Core\FieldType\DateAndTime\Value as DateAndTimeValue;

abstract class Schedule extends ValueObject
{
    /** @var DateAndTimeValue */
    protected $schedule_time;
}
