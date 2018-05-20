<?php

namespace Edgar\EzCampaign\Tab\Reports;

use EzSystems\EzPlatformAdminUi\Tab\AbstractTab;
use EzSystems\EzPlatformAdminUi\Tab\OrderedTabInterface;

class AdviceTab extends AbstractTab implements OrderedTabInterface
{
    public function getIdentifier(): string
    {
        return 'reports-advice';
    }

    public function getName(): string
    {
        return /* @Desc("Advice") */
            $this->translator->trans('campaign.reports.name.advice', [], 'edgarezcampaign');
    }

    public function getOrder(): int
    {
        return 200;
    }

    public function renderView(array $parameters): string
    {
        return '';
    }
}
