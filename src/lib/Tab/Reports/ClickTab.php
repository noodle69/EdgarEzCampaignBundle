<?php

namespace Edgar\EzCampaign\Tab\Reports;

use Edgar\EzCampaign\Values\API\Campaign;
use Edgar\EzCampaignBundle\Service\ReportsService;
use EzSystems\EzPlatformAdminUi\Tab\AbstractTab;
use EzSystems\EzPlatformAdminUi\Tab\OrderedTabInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment;

class ClickTab extends AbstractTab implements OrderedTabInterface
{
    /** @var ReportsService */
    protected $reportsService;

    /**
     * ClickTab constructor.
     *
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
        return 'reports-click';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return /* @Desc("Click") */
            $this->translator->trans('campaign.reports.name.click', [], 'edgarezcampaign');
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 400;
    }

    /**
     * @param array $parameters
     *
     * @return string
     *
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

        $clicks = $this->reportsService->getClick($campaign->getId());

        return $this->twig->render('EdgarEzCampaignBundle:campaign/reports/tabs:click.html.twig', [
            'clicks' => $clicks,
        ]);
    }
}
