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

    /**
     * AbuseTab constructor.
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
        return 'reports-abuse';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return /* @Desc("Abuse") */
            $this->translator->trans('campaign.reports.name.abuse', [], 'edgarezcampaign');
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 100;
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

        $abuses = $this->reportsService->getAbuses($campaign->getId());

        return $this->twig->render('EdgarEzCampaignBundle:campaign/reports/tabs:abuses.html.twig', [
            'abuses' => $abuses,
        ]);
    }
}
