<?php

namespace Edgar\EzCampaign\Tab\Reports;

use Edgar\EzCampaign\Values\Core\Campaign;
use Edgar\EzCampaignBundle\Service\ReportsService;
use EzSystems\EzPlatformAdminUi\Tab\AbstractTab;
use EzSystems\EzPlatformAdminUi\Tab\OrderedTabInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment;

class AbuseTab extends AbstractTab implements OrderedTabInterface
{
    /** @var ReportsService  */
    protected $reportsService;

    public function __construct(
        Environment $twig,
        TranslatorInterface $translator,
        ReportsService $reportsService
    ) {
        parent::__construct($twig, $translator);
        $this->reportsService = $reportsService;
    }

    public function getIdentifier(): string
    {
        return 'reports-abuse';
    }

    public function getName(): string
    {
        return /* @Desc("Abuse") */
            $this->translator->trans('campaign.reports.name.abuse', [], 'edgarezcampaign');
    }

    public function getOrder(): int
    {
        return 100;
    }

    public function renderView(array $parameters): string
    {
        $campaign = (isset($parameters['campaign']) && $parameters['campaign'] instanceof Campaign)
            ? $parameters['campaign'] : null;

        if (!$campaign) {
            return '';
        }

        $abuses = $this->reportsService->getAbuses($campaign->getId());

        return $this->twig->render('EdgarEzCampaignBundle:campaign/reports/tabs:abuses.html.twig', [
            'abuses' => $abuses,
        ]);
    }
}
