<?php

namespace Edgar\EzCampaignBundle\Controller;

use Edgar\EzCampaignBundle\Service\ListsService;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;

class ListController extends Controller
{
    protected $listsService;

    public function __construct(ListsService $listsService)
    {
        $this->listsService = $listsService;
    }

    public function listsAction()
    {
        $lists = $this->listsService->get();

        return $this->render('@EdgarEzCampaign/campaign/lists.html.twig', [
            'lists' => $lists,
        ]);
    }

    public function editAction(?int $listID)
    {

    }
}
