<?php

namespace Edgar\EzCampaign\Tab\Reports;

use EzSystems\EzPlatformAdminUi\Tab\AbstractTab;
use EzSystems\EzPlatformAdminUi\Tab\OrderedTabInterface;

class OpenTab extends AbstractTab implements OrderedTabInterface
{
    public function getIdentifier(): string
    {
        return 'reports-open';
    }

    public function getName(): string
    {
        return /* @Desc("Open") */
            $this->translator->trans('campaign.reports.name.open', [], 'edgarezcampaign');
    }

    public function getOrder(): int
    {
        return 300;
    }

    public function renderView(array $parameters): string
    {
        return '';
    }
}
