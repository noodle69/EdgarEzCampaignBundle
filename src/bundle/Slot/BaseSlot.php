<?php

namespace Edgar\EzCampaignBundle\Slot;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\ORMException;
use Edgar\EzCampaign\Repository\EdgarEzCampaignRepository;
use Edgar\EzCampaignBundle\Entity\EdgarEzCampaign;
use Edgar\EzCampaignBundle\Service\CampaignService;
use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\Exceptions\BadStateException;
use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use eZ\Publish\API\Repository\Exceptions\UnauthorizedException;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\Core\SignalSlot\Slot;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Welp\MailchimpBundle\Exception\MailchimpException;

abstract class BaseSlot extends Slot
{
    /** @var ContentService */
    protected $contentService;

    /** @var LocationService */
    protected $locationService;

    /** @var RouterInterface */
    private $router;

    /** @var CampaignService */
    private $campaignService;

    /** @var EdgarEzCampaignRepository */
    private $campaignRepository;

    /**
     * BaseSlot constructor.
     *
     * @param ContentService $contentService
     * @param LocationService $locationService
     * @param RouterInterface $router
     * @param CampaignService $campaignService
     * @param Registry $doctrineRegistry
     */
    public function __construct(
        ContentService $contentService,
        LocationService $locationService,
        RouterInterface $router,
        CampaignService $campaignService,
        Registry $doctrineRegistry
    ) {
        $this->contentService = $contentService;
        $this->locationService = $locationService;
        $this->router = $router;
        $this->campaignService = $campaignService;

        $entityManager = $doctrineRegistry->getManager();
        $this->campaignRepository = $entityManager->getRepository(EdgarEzCampaign::class);
    }

    /**
     * @param int $contentId
     */
    protected function updateCampaignContent(int $contentId)
    {
        try {
            $content = $this->contentService->loadContent($contentId);
            /** @var Location[] $locations */
            $locations = $this->locationService->loadLocations($content->contentInfo);
            /** @var EdgarEzCampaign[] $campaigns */
            $campaigns = $this->campaignRepository->getCampaigns($locations);
        } catch (NotFoundException | UnauthorizedException | BadStateException $e) {
            $campaigns = [];
        }

        foreach ($campaigns as $campaign) {
            $url = $this->router->generate(
                'edgar.campaign.view',
                [
                    'locationId' => $campaign->getLocationId(),
                    'site' => $campaign->getSite(),
                    'siteaccess' => $campaign->getSite(),
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            try {
                $this->campaignService->putContent(
                    $campaign->getCampaignId(),
                    $url,
                    $campaign->getLocationId(),
                    $campaign->getSite()
                );
            } catch (MailchimpException | ORMException $e) {
            }
        }
    }
}
