<?php

namespace Edgar\EzCampaign\Tab\Reports;

use Edgar\EzCampaignBundle\Service\ReportsService;
use EzSystems\EzPlatformAdminUi\Tab\AbstractTab;
use EzSystems\EzPlatformAdminUi\Tab\OrderedTabInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment;

class AdviceTab extends AbstractTab implements OrderedTabInterface
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
        $campaign = (isset($parameters['campaign']) && $parameters['campaign'] instanceof Campaign)
            ? $parameters['campaign'] : null;

        if (!$campaign) {
            return '';
        }

        $advice = $this->reportsService->getAdvice($campaign->getId());

        return $this->twig->render('EdgarEzCampaignBundle:campaign/reports/tabs:advice.html.twig', [
            'advice' => $advice,
        ]);
    }
}
