<?php

namespace Edgar\EzCampaign\Tab\Reports;

use Edgar\EzCampaign\Values\Core\Campaign;
use Edgar\EzCampaignBundle\Service\ReportsService;
use EzSystems\EzPlatformAdminUi\Tab\AbstractTab;
use EzSystems\EzPlatformAdminUi\Tab\OrderedTabInterface;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment;

class ClickTab extends AbstractTab implements OrderedTabInterface
{
    /** @var ReportsService */
    protected $reportsService;

    /** @var int */
    protected $defaultPaginationLimit;

    /**
     * ClickTab constructor.
     *
     * @param Environment $twig
     * @param TranslatorInterface $translator
     * @param ReportsService $reportsService
     * @param int $defaultPaginationLimit
     */
    public function __construct(
        Environment $twig,
        TranslatorInterface $translator,
        ReportsService $reportsService,
        int $defaultPaginationLimit
    ) {
        parent::__construct($twig, $translator);
        $this->reportsService = $reportsService;
        $this->defaultPaginationLimit = $defaultPaginationLimit;
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
        $page = $parameters['page'] ?? 1;

        /** @var Campaign $campaign */
        $campaign = (isset($parameters['campaign']) && $parameters['campaign'] instanceof Campaign)
            ? $parameters['campaign'] : null;

        if (!$campaign) {
            return '';
        }

        $allClicks = $this->reportsService->getClick($campaign->getId());

        $pagerfanta = new Pagerfanta(
            new ArrayAdapter($allClicks['urls_clicked'])
        );

        $pagerfanta->setMaxPerPage($this->defaultPaginationLimit);
        $pagerfanta->setCurrentPage(min($page, $pagerfanta->getNbPages()));

        $clicks = $this->reportsService->getClick(
            $campaign->getId(),
            $this->defaultPaginationLimit * ($page - 1),
            $this->defaultPaginationLimit
        );

        return $this->twig->render('EdgarEzCampaignBundle:campaign/reports/tabs:click.html.twig', [
            'campaign_id' => $campaign->getId(),
            'clicks' => $clicks,
            'pager' => $pagerfanta,
        ]);
    }
}
