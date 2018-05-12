<?php

namespace Edgar\EzCampaign\Data;

use Edgar\EzCampaign\Values\API\Reports;

trait ReportsDataTrait
{
    /**
     * @var Reports $reports
     */
    protected $reports;

    public function setReports(Reports $reports)
    {
        $this->reports = $reports;
    }
}
