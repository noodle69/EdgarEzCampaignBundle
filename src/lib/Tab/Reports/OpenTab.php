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

class OpenTab extends AbstractTab implements OrderedTabInterface
{
    /** @var ReportsService */
    protected $reportsService;

    /** @var int */
    protected $defaultPaginationLimit;

    /**
     * OpenTab constructor.
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
        return 'reports-open';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return /* @Desc("Open") */
            $this->translator->trans('campaign.reports.name.open', [], 'edgarezcampaign');
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 300;
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

        $campaign = (isset($parameters['campaign']) && $parameters['campaign'] instanceof Campaign)
            ? $parameters['campaign'] : null;

        if (!$campaign) {
            return '';
        }

        $allOpens = $this->reportsService->getOpen($campaign->getId());

        $pagerfanta = new Pagerfanta(
            new ArrayAdapter($allOpens['members'])
        );

        $pagerfanta->setMaxPerPage($this->defaultPaginationLimit);
        $pagerfanta->setCurrentPage(min($page, $pagerfanta->getNbPages()));

        $opens = $this->reportsService->getOpen(
            $campaign->getId(),
            $this->defaultPaginationLimit * ($page - 1),
            $this->defaultPaginationLimit
        );

        return $this->twig->render('EdgarEzCampaignBundle:campaign/reports/tabs:open.html.twig', [
            'campaign_id' => $campaign->getId(),
            'opens' => $opens,
            'pager' => $pagerfanta,
        ]);
    }
}
