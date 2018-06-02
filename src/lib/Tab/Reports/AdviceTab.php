<?php

namespace Edgar\EzCampaign\Tab\Reports;

use Edgar\EzCampaign\Values\API\Campaign;
use Edgar\EzCampaignBundle\Service\ReportsService;
use EzSystems\EzPlatformAdminUi\Tab\AbstractTab;
use EzSystems\EzPlatformAdminUi\Tab\OrderedTabInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment;

class AdviceTab extends AbstractTab implements OrderedTabInterface
{
    /** @var ReportsService  */
    protected $reportsService;

    /**
     * AdviceTab constructor.
     * @param Environment $twig
     * @param TranslatorInterface $translator
     * @param ReportsService $reportsService
     */
    public function __construct(
        Environment $twig,
        TranslatorInterface $translator,
        ReportsService $reportsService
    ) {
        parent::__construct($twig, $translator);
        $this->reportsService = $reportsService;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return 'reports-advice';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return /* @Desc("Advice") */
            $this->translator->trans('campaign.reports.name.advice', [], 'edgarezcampaign');
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 200;
    }

    /**
     * @param array $parameters
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
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
