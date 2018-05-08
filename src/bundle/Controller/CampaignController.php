<?php

namespace Edgar\EzCampaignBundle\Controller;

use Edgar\EzCampaignBundle\Service\CampaignsService;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;

/**
 * Class CampaignController
 *
 * @package Edgar\EzCampaignBundle\Controller
 */
class CampaignController extends Controller
{
    protected $campaignsService;

    public function __construct(CampaignsService $campaignsService)
    {
        $this->campaignsService = $campaignsService;
    }

    public function campaignsAction()
    {
        $campaigns = $this->campaignsService->get();

        return $this->render('@EdgarEzCampaign/campaign/campaigns.html.twig', [
            'campaigns' => $campaigns,
        ]);
    }

    public function editAction(?int $campaignID)
    {

    }
}
