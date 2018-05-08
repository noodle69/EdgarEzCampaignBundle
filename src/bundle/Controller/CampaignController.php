<?php

namespace Edgar\EzCampaignBundle\Controller;

use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;

/**
 * Class CampaignController
 *
 * @package Edgar\EzCampaignBundle\Controller
 */
class CampaignController extends Controller
{
    public function campaignsAction()
    {
        return $this->render('@EdgarEzCampaign/campaign/campaigns.html.twig', [
        ]);
    }
}
