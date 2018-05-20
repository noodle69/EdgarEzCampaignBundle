<?php

namespace Edgar\EzCampaign\Tab\Reports;

use EzSystems\EzPlatformAdminUi\Tab\AbstractTab;
use EzSystems\EzPlatformAdminUi\Tab\OrderedTabInterface;

class ClickTab extends AbstractTab implements OrderedTabInterface
{
    public function getIdentifier(): string
    {
        return 'reports-click';
    }

    public function getName(): string
    {
        return /* @Desc("Click") */
            $this->translator->trans('campaign.reports.name.click', [], 'edgarezcampaign');
    }

    public function getOrder(): int
    {
        return 400;
    }

    public function renderView(array $parameters): string
    {
        return '';
    }
}
