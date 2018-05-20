<?php

namespace Edgar\EzCampaign\Data;

use Edgar\EzCampaign\Values\API\Reports;

trait ReportsDataTrait
{
    /**
     * @var Reports
     */
    protected $reports;

    /**
     * @param Reports $reports
     */
    public function setReports(Reports $reports)
    {
        $this->reports = $reports;
    }
}
