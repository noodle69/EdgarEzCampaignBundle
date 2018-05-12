<?php

namespace Edgar\EzCampaign\Tab\Reports;

use EzSystems\EzPlatformAdminUi\Tab\AbstractTab;
use EzSystems\EzPlatformAdminUi\Tab\OrderedTabInterface;

class AbuseTab extends AbstractTab implements OrderedTabInterface
{
    public function getIdentifier(): string
    {
        return 'reports-abuse';
    }

    public function getName(): string
    {
        return /** @Desc("Abuse") */
            $this->translator->trans('campaign.reports.name.abuse', [], 'edgarezcampaign');
    }

    public function getOrder(): int
    {
        return 100;
    }

    public function renderView(array $parameters): string
    {
        return '';
    }
}
