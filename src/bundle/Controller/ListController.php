<?php

namespace Edgar\EzCampaignBundle\Controller;

use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;

class ListController extends Controller
{
    public function listsAction()
    {
        return $this->render('@EdgarEzCampaign/campaign/lists.html.twig', [
        ]);
    }
}
