<?php

namespace Edgar\EzCampaign\Values\Core;

use eZ\Publish\Core\FieldType\DateAndTime\Value as DateAndTimeValue;

class Schedule extends \Edgar\EzCampaign\Values\API\Schedule
{
    public function getScheduleTime(): ?DateAndTimeValue
    {
        return $this->schedule_time;
    }

    public function setScheduleTime(?DateAndTimeValue $schedule_time): self
    {
        $this->schedule_time = $schedule_time;

        return $this;
    }
}
