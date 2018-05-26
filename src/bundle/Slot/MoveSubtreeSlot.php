<?php

namespace Edgar\EzCampaignBundle\Slot;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Edgar\EzCampaign\Repository\EdgarEzCampaignRepository;
use Edgar\EzCampaignBundle\Service\CampaignService;
use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use eZ\Publish\API\Repository\Exceptions\UnauthorizedException;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\Core\SignalSlot\Signal;
use Symfony\Component\Routing\RouterInterface;

class MoveSubtreeSlot extends BaseSlot
{
    public function __construct(
        ContentService $contentService,
        LocationService $locationService,
        RouterInterface $router,
        CampaignService $campaignService,
        Registry $doctrineRegistry
    ) {
        parent::__construct($contentService, $locationService, $router, $campaignService, $doctrineRegistry);
    }

    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\LocationService\MoveSubtreeSignal) {
            return;
        }

        try {
            $location = $this->locationService->loadLocation($signal->locationId);

            $this->updateCampaignContent($location->contentId);
        } catch(UnauthorizedException|NotFoundException $e) {
            return;
        }
    }
}
